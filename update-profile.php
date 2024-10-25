<?php
session_start();
include 'auth.php';
include 'db.php';

$response = array('status' => '', 'message' => '');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$username = $_POST['username'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];

$fullName = "$firstName $lastName";

// Lấy mật khẩu hiện tại từ cơ sở dữ liệu
$sql = "SELECT PASSWORD FROM user WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// So sánh mật khẩu hiện tại trực tiếp
if ($currentPassword === $user['PASSWORD']) {
    if (!empty($newPassword) && $newPassword === $confirmPassword) {
        // Cập nhật mật khẩu mới
        $sql = "UPDATE user SET NAME = ?, USERNAME = ?, PASSWORD = ?, PHONE = ?, EMAIL = ?, ADDRESS = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $fullName, $username, $newPassword, $phone, $email, $address, $userId);
    } else {
        // Không thay đổi mật khẩu nếu trường mới không được điền hoặc không khớp
        $sql = "UPDATE user SET NAME = ?, USERNAME = ?, PHONE = ?, EMAIL = ?, ADDRESS = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $fullName, $username, $phone, $email, $address, $userId);
    }

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Cập nhật thông tin thành công!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Có lỗi xảy ra, vui lòng thử lại.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Mật khẩu hiện tại không đúng.';
}

$conn->close();

echo json_encode($response);
?>