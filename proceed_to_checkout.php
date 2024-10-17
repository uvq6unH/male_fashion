<?php
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$userId = $_SESSION['user_id'];

// Cập nhật cột NOTES thành 'pending' cho đơn hàng hiện tại của người dùng
$sql_update_notes = "UPDATE orders SET NOTES = 'pending' WHERE IDUSER = ? AND NOTES IS NULL ORDER BY ID DESC LIMIT 1";
$stmt_update_notes = $conn->prepare($sql_update_notes);
$stmt_update_notes->bind_param("i", $userId);
$stmt_update_notes->execute();

// Đóng kết nối
$stmt_update_notes->close();
$conn->close();

// Chuyển hướng đến checkout.php
header("Location: checkout.php");
exit();
