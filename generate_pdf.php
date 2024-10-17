<?php
require('v-dashboard/TCPDF/tcpdf.php');
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu
include 'discount.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Lấy thông tin từ POST request
$paymentMethod = $_POST['paymentMethod'] ?? 'Visa';
$transportMethod = $_POST['transportMethod'] ?? 'Standard Delivery';

// Định nghĩa các ID tương ứng với phương thức thanh toán và vận chuyển
$paymentMethods = ['Visa' => 1, 'MasterCard' => 2];
$transportMethods = ['Standard Delivery' => 1, 'Express Delivery' => 2];

$IDPAYMENT = $paymentMethods[$paymentMethod];
$IDTRANSPORT = $transportMethods[$transportMethod];

// Lấy thông tin người dùng
$userQuery = "SELECT name, address, email, phone FROM user WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$userInfo = $userResult->fetch_assoc();

// Cập nhật thông tin người nhận và phương thức thanh toán, vận chuyển
$sql_update_order = "UPDATE orders SET 
    RECIPIENT_NAME = ?, 
    address = ?, 
    phone = ?, 
    IDPAYMENT = ?, 
    IDTRANSPORT = ? 
    WHERE IDUSER = ? AND NOTES = 'pending' ORDER BY ID DESC LIMIT 1";
$stmt = $conn->prepare($sql_update_order);
$stmt->bind_param(
    "sssiii",
    $userInfo['name'],
    $userInfo['address'],
    $userInfo['phone'],
    $IDPAYMENT,
    $IDTRANSPORT,
    $userId
);
$stmt->execute();

// Cập nhật trạng thái đơn hàng từ 'pending' sang 'done'
$sql_update_status = "UPDATE orders SET NOTES = 'done' WHERE IDUSER = ? AND NOTES = 'pending' ORDER BY ID DESC LIMIT 1";
$stmt = $conn->prepare($sql_update_status);
$stmt->bind_param("i", $userId);
$stmt->execute();

// Lấy thông tin sản phẩm trong giỏ hàng
$cartQuery = "SELECT p.name, od.quantity, p.price
FROM orders o
JOIN orders_details od ON o.id = od.IDORDER
JOIN product p ON od.IDPRODUCT = p.id
WHERE o.IDUSER = ? AND o.NOTES = 'done'";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartResult = $stmt->get_result();

// Khởi tạo TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// Đầu hóa đơn
$pdf->Image(__DIR__ . '/img/logo.jpg', 10, 10, 30, '', 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->SetFont('dejavusans', 'B', 20);
$pdf->Cell(0, 10, 'Male-Fashion', 0, 1, 'C');
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0, 10, 'Address: No Where', 0, 1, 'C');
$pdf->Cell(0, 10, 'Phone: 0123456789', 0, 1, 'C');
$pdf->Cell(0, 10, 'Email: male-fashion@shop.com', 0, 1, 'C');
$pdf->Ln(10);

// Thông tin cá nhân
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Customer Information', 0, 1);
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0, 10, 'Name: ' . $userInfo['name'], 0, 1);
$pdf->Cell(0, 10, 'Address: ' . $userInfo['address'], 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $userInfo['email'], 0, 1);
$pdf->Cell(0, 10, 'Phone: ' . $userInfo['phone'], 0, 1);
$pdf->Ln(5);

// Thông tin sản phẩm
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Product Information', 0, 1);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(120, 10, 'Product Name', 1, 0);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
$pdf->Cell(40, 10, 'Price', 1, 0, 'C');
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 12);

$totalAmount = 0;
while ($cartItem = $cartResult->fetch_assoc()) {
    $productTotal = $cartItem['quantity'] * $cartItem['price'];
    $totalAmount += $productTotal;
    $pdf->Cell(120, 10, $cartItem['name'], 1);
    $pdf->Cell(30, 10, $cartItem['quantity'], 1);
    $pdf->Cell(40, 10, '$' . number_format($cartItem['price'], 2), 1);
    $pdf->Ln();
}

$discountAmount = 0;
if (isset($_SESSION['discount'])) {
    $discountPercentage = $_SESSION['discount'];
    $discountAmount = ($totalAmount * $discountPercentage) / 100;
    $totalAfterDiscount = $totalAmount - $discountAmount;
    $pdf->Cell(150, 10, "Subtotal", 1);
    $pdf->Cell(40, 10, '$' . number_format($totalAmount, 2), 1);
    $pdf->Ln();
    $pdf->Cell(150, 10, "Discount ({$discountPercentage}%)", 1);
    $pdf->Cell(40, 10, '-$' . number_format($discountAmount, 2), 1);
    $pdf->Ln();
    $pdf->Cell(150, 10, 'Total', 1);
    $pdf->Cell(40, 10, '$' . number_format($totalAfterDiscount, 2), 1);
    $pdf->Ln(10);
}else{
    $totalAfterDiscount=$totalAmount;
    $pdf->Cell(150, 10, "Subtotal", 1);
    $pdf->Cell(40, 10, '$' . number_format($totalAmount, 2), 1);
    $pdf->Ln();
    $pdf->Cell(150, 10, 'Total', 1);
    $pdf->Cell(40, 10, '$' . number_format($totalAfterDiscount, 2), 1);
    $pdf->Ln(10);

}
unset($_SESSION['discount']);


$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Shop Information', 0, 1);
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0, 10, 'Shop Name: Male-Fashion', 0, 1);
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 10, 'Free shipping, 30-day return or refund guarantee.', 0, 1);

$pdf->Output('order_details.pdf', 'D');
