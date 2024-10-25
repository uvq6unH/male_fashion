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
$product_id = $_GET['id']; // Lấy ID sản phẩm để xóa

// Truy vấn để lấy số lượng hiện tại của sản phẩm trong giỏ hàng
$sql = "SELECT QUANTITY, PRICE FROM orders_details 
        WHERE IDPRODUCT = ? 
        AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? AND NOTES = 'pending' LIMIT 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_quantity = $row['QUANTITY'];
    $product_price = $row['PRICE'];

    // Kiểm tra nếu số lượng hiện tại lớn hơn 1
    if ($current_quantity > 1) {
        // Giảm số lượng xuống 1 và cập nhật giá tương ứng
        $sql = "UPDATE orders_details 
                SET QUANTITY = QUANTITY - 1,
                    PRICE = PRICE - (PRICE / ?) -- Cập nhật giá dựa trên số lượng
                WHERE IDPRODUCT = ? 
                AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? AND NOTES = 'pending' LIMIT 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $current_quantity, $product_id, $userId);
        $stmt->execute();
        $message = "Đã xóa 1 sản phẩm khỏi giỏ hàng của bạn. Bạn hiện có " . ($current_quantity - 1) . " sản phẩm này còn lại.";
    } else {
        // Nếu số lượng là 1, xóa sản phẩm khỏi giỏ hàng hoàn toàn
        $sql = "DELETE FROM orders_details 
                WHERE IDPRODUCT = ? AND IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? AND NOTES = 'pending' LIMIT 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $product_id, $userId);
        $stmt->execute();
        $message = "Sản phẩm đã được xóa khỏi giỏ hàng của bạn.";
    }

    // Cập nhật tổng số tiền trong bảng orders
    // Lấy tổng giá đơn hàng hiện tại từ orders_details
    $sql_total = "SELECT SUM(PRICE) AS total_money FROM orders_details 
                  WHERE IDORDER = (SELECT ID FROM orders WHERE IDUSER = ? AND NOTES = 'pending' LIMIT 1)";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("i", $userId);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_money = $result_total->fetch_assoc()['total_money'];

    // Kiểm tra xem mã giảm giá có trong session không
    $discount = $_SESSION['discount'] ?? 0; // Nếu không có mã giảm giá, discount sẽ là 0%

    // Áp dụng mã giảm giá (nếu có)
    if ($discount > 0) {
        $total_money = $total_money - ($total_money * ($discount / 100)); // Áp dụng giảm giá
    }

    // Làm tròn số tiền đến 2 chữ số thập phân
    $total_money = round($total_money, 2);

    // Cập nhật tổng tiền vào bảng orders
    $sql_update_order = "UPDATE orders SET TOTAL_MONEY = ? WHERE IDUSER = ? AND NOTES = 'pending' ORDER BY ID DESC LIMIT 1";
    $stmt_update_order = $conn->prepare($sql_update_order);
    $stmt_update_order->bind_param("di", $total_money, $userId);
    $stmt_update_order->execute();
} else {
    $message = "Không có sản phẩm nào trong giỏ hàng.";
}

// Đóng kết nối
$stmt->close();
$conn->close();

// Chuyển hướng lại trang giỏ hàng với thông điệp
header("Location: shopping-cart.php?message=" . urlencode($message));
exit();
