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
$product_id = $_POST['product_id']; // Assuming you're sending product ID via POST
$quantity = 1; // Increment by 1 for each add-to-cart action

// Check if the product already exists in the cart
$sql = "SELECT QUANTITY FROM orders_details 
        WHERE IDPRODUCT = ? AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Product already in cart, increment quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['QUANTITY'] + $quantity;

    $sql = "UPDATE orders_details 
            SET QUANTITY = ? 
            WHERE IDPRODUCT = ? AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $new_quantity, $product_id, $user_id);
    $stmt->execute();
    $message = "Quantity updated.";
} else {
    // Product not in cart, add it
    $sql = "INSERT INTO orders_details (IDORDER, IDPRODUCT, QUANTITY) 
            VALUES ((SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    $message = "Product added to cart.";
}

// Close the connection
$stmt->close();
$conn->close();

// Redirect back to the shop or wherever you want with a success message
header("Location: shop.php?message=" . urlencode($message));
exit();
