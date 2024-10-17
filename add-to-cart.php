<?php
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$product_id = $_POST['product_id']; // Giả sử ID sản phẩm được gửi qua POST
$quantity = 1; // Mỗi lần thêm vào giỏ tăng 1 đơn vị

// Kiểm tra nếu có đơn hàng 'pending' cho người dùng
$sql = "SELECT ID FROM orders WHERE IDUSER = ? AND NOTES = 'pending' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    $order_id = $order['ID'];
} else {
    $sql = "INSERT INTO orders (IDUSER, ORDERS_DATE, NOTES) VALUES (?, NOW(), 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $order_id = $stmt->insert_id;
}

// Lấy giá sản phẩm từ bảng product
$sql = "SELECT PRICE FROM product WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$product_price = $product['PRICE'];

// Tính tổng giá (giá sản phẩm * số lượng)
$total_price = $product_price * $quantity;

// Kiểm tra nếu sản phẩm đã có trong giỏ hàng
$sql = "SELECT QUANTITY FROM orders_details 
        WHERE IDPRODUCT = ? AND IDORDER = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Sản phẩm đã có trong giỏ, tăng số lượng và cập nhật giá
    $row = $result->fetch_assoc();
    $new_quantity = $row['QUANTITY'] + $quantity;
    $new_total_price = $product_price * $new_quantity; // Cập nhật tổng giá

    $sql = "UPDATE orders_details 
            SET QUANTITY = ?, PRICE = ? 
            WHERE IDPRODUCT = ? AND IDORDER = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idii", $new_quantity, $new_total_price, $product_id, $order_id);
    $stmt->execute();
    $message = "Số lượng và giá đã được cập nhật.";
} else {
    // Sản phẩm chưa có trong giỏ, thêm mới với giá đã tính
    $sql = "INSERT INTO orders_details (IDORDER, IDPRODUCT, QUANTITY, PRICE) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $total_price);
    $stmt->execute();
    $message = "Sản phẩm đã được thêm vào giỏ hàng.";
}

// Đóng kết nối
$stmt->close();
$conn->close();

// Chuyển hướng trở lại trang cửa hàng hoặc nơi bạn muốn với thông báo thành công
header("Location: shop.php?message=" . urlencode($message));
exit();
