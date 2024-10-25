<?php
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu

// Điều này phải ở rất đầu
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra xem có mã giảm giá đã chọn không
$discountCodes = $_SESSION['selected_discounts'] ?? []; // Sử dụng mảng rỗng nếu không được thiết lập

// Lấy và làm sạch mã coupon
$couponCode = strtoupper(trim($_POST['coupon_code'] ?? '')); // Chuyển sang chữ hoa và loại bỏ khoảng trắng

// Kiểm tra xem mã có hợp lệ không
if (!empty($discountCodes) && array_key_exists($couponCode, $discountCodes)) {
    // Nếu hợp lệ, lưu phần trăm giảm giá vào phiên
    $_SESSION['discount'] = $discountCodes[$couponCode];

    // Lấy ID người dùng từ phiên
    $userId = $_SESSION['user_id'];

    // Lấy ID của đơn hàng 'pending' gần nhất của người dùng
    $sql_order_id = "SELECT ID FROM orders WHERE IDUSER = ? AND NOTES = 'pending' ORDER BY ID DESC LIMIT 1";
    $stmt_order_id = $conn->prepare($sql_order_id);
    $stmt_order_id->bind_param("i", $userId);
    $stmt_order_id->execute();
    $result_order_id = $stmt_order_id->get_result();
    $order_id = $result_order_id->fetch_assoc()['ID'];

    // Lấy tổng giá của đơn hàng từ orders_details
    $sql_total = "SELECT SUM(PRICE) AS total_money FROM orders_details WHERE IDORDER = ? ";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("i", $order_id);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_money = $result_total->fetch_assoc()['total_money'];

    // Áp dụng giảm giá vào total_money
    $discount = $_SESSION['discount'];
    if ($discount > 0) {
        $total_money = $total_money - ($total_money * ($discount / 100));
    }

    // Làm tròn total_money đến 2 chữ số thập phân
    $total_money = round($total_money, 2);

    // Cập nhật total_money trong bảng orders, không phụ thuộc vào giá trị trước đó
    $sql_update_order = "UPDATE orders SET TOTAL_MONEY = ? WHERE IDUSER = ? ORDER BY ID DESC LIMIT 1";
    $stmt_update_order = $conn->prepare($sql_update_order);
    $stmt_update_order->bind_param("di", $total_money, $userId);
    $stmt_update_order->execute();

    // Đóng kết nối
    $stmt_total->close();
    $stmt_update_order->close();
    $conn->close();
    // Chuyển hướng với thông báo thành công
    header("Location: shopping-cart.php?message=Giảm giá đã được áp dụng: {$discount}%");
    exit(); // Thoát sau khi chuyển hướng
} else {
    // Nếu không hợp lệ, chuyển hướng với thông báo lỗi
    header("Location: shopping-cart.php?message=Mã coupon không hợp lệ!");
    exit(); // Thoát sau khi chuyển hướng
}
