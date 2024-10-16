<?php
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu
include 'discount.php';
// This must be at the very top

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if selected discounts exist
$discountCodes = $_SESSION['selected_discounts'] ?? []; // Use empty array if not set

// Debugging output
echo '<pre>';
print_r($_SESSION); // Check the entire session data
echo '</pre>';

// Fetch and sanitize the coupon code
$couponCode = strtoupper(trim($_POST['coupon_code'] ?? '')); // Convert to uppercase and trim spaces

// Check if the discount code is valid
if (!empty($discountCodes) && array_key_exists($couponCode, $discountCodes)) {
    // If valid, save the discount percentage into the session
    $_SESSION['discount'] = $discountCodes[$couponCode];
    header("Location: shopping-cart.php?message=Discount applied: {$discountCodes[$couponCode]}%");
    exit(); // Exit after redirecting
} else {
    // If invalid, redirect with error message
    header("Location: shopping-cart.php?message=Invalid coupon code!");
    exit(); // Exit after redirecting
}
