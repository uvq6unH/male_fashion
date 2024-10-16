<?php
session_start();
include 'auth.php'; // User authentication
include 'db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id']; // Get the product ID to remove

// Query to get the current quantity of the product in the cart
$sql = "SELECT QUANTITY FROM orders_details 
        WHERE IDPRODUCT = ? AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_quantity = $row['QUANTITY'];

    // Check if the current quantity is greater than 1
    if ($current_quantity > 1) {
        // Decrement the quantity by 1
        $sql = "UPDATE orders_details 
                SET QUANTITY = QUANTITY - 1 
                WHERE IDPRODUCT = ? AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $message = "1 item has been removed from your cart. You now have " . ($current_quantity - 1) . " of this item left.";
    } else {
        // If the quantity is 1, remove the product from the cart entirely
        $sql = "DELETE FROM orders_details 
                WHERE IDPRODUCT = ? AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $message = "The product has been removed from your cart.";
    }
} else {
    $message = "No items were found in the cart.";
}

// Close the connection
$stmt->close();
$conn->close();

// Redirect back to the shopping cart with the message
header("Location: shopping-cart.php?message=" . urlencode($message));
exit();
