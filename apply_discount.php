<?php
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu

// This must be at the very top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if selected discounts exist
$discountCodes = $_SESSION['selected_discounts'] ?? []; // Use empty array if not set

// Fetch and sanitize the coupon code
$couponCode = strtoupper(trim($_POST['coupon_code'] ?? '')); // Convert to uppercase and trim spaces

// Check if the code is valid
if (!empty($discountCodes) && array_key_exists($couponCode, $discountCodes)) {
    // If valid, save the discount percentage into the session
    $_SESSION['discount'] = $discountCodes[$couponCode];

    // Get the user ID from session
    $userId = $_SESSION['user_id'];

    // Fetch the total price of the order from orders_details
    $sql_total = "SELECT SUM(PRICE * QUANTITY) AS total_money FROM orders_details WHERE IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? LIMIT 1)";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("i", $userId);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_money = $result_total->fetch_assoc()['total_money'];

    // Apply the discount to the total_money
    $discount = $_SESSION['discount'];
    if ($discount > 0) {
        $total_money = $total_money - ($total_money * ($discount / 100));
    }

    // Round the total_money to 2 decimal places
    $total_money = round($total_money, 2);

    // Update the total_money in the orders table, regardless of previous value
    $sql_update_order = "UPDATE orders SET TOTAL_MONEY = ? WHERE IDUSER = ? ORDER BY ID DESC LIMIT 1";
    $stmt_update_order = $conn->prepare($sql_update_order);
    $stmt_update_order->bind_param("di", $total_money, $userId);
    $stmt_update_order->execute();

    // Close connections
    $stmt_total->close();
    $stmt_update_order->close();
    $conn->close();

    // Redirect with success message
    header("Location: shopping-cart.php?message=Discount-applied:{$discount}%");
    exit(); // Exit after redirecting
} else {
    // If invalid, redirect with error message
    header("Location: shopping-cart.php?message=Invalid-coupon-code!");
    exit(); // Exit after redirecting
}
