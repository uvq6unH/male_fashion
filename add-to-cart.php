<?php
session_start();
include 'auth.php'; // Xác thực người dùng
include 'db.php'; // Kết nối cơ sở dữ liệu

header('Content-Type: application/json'); // Đặt header cho phản hồi JSON

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.']);
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
$total_money = $product_price * $quantity;

// Kiểm tra nếu sản phẩm đã có trong giỏ hàng
$sql = "SELECT QUANTITY FROM orders_details WHERE IDPRODUCT = ? AND IDORDER = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Sản phẩm đã có trong giỏ, tăng số lượng và cập nhật giá
    $row = $result->fetch_assoc();
    $new_quantity = $row['QUANTITY'] + $quantity;
    $new_total_price = $product_price * $new_quantity;

    $sql = "UPDATE orders_details SET QUANTITY = ?, PRICE = ? WHERE IDPRODUCT = ? AND IDORDER = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idii", $new_quantity, $new_total_price, $product_id, $order_id);
    $stmt->execute();
    $message = "Số lượng và giá đã được cập nhật.";
} else {
    // Sản phẩm chưa có trong giỏ, thêm mới với giá đã tính
    $sql = "INSERT INTO orders_details (IDORDER, IDPRODUCT, QUANTITY, PRICE) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $total_money);
    $stmt->execute();
    $message = "Sản phẩm đã được thêm vào giỏ hàng.";
}

// Cập nhật tổng tiền trong bảng orders
$sql_total = "SELECT SUM(PRICE) AS total_money FROM orders_details WHERE IDORDER = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("i", $order_id);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$current_total_money = $result_total->fetch_assoc()['total_money'];

// Kiểm tra xem mã giảm giá có trong session không
$discount = $_SESSION['discount'] ?? 0;

if ($discount > 0) {
    $current_total_money -= ($current_total_money * ($discount / 100));
}

$current_total_money = round($current_total_money, 2);

// Cập nhật tổng tiền vào bảng orders
$sql_update_order = "UPDATE orders SET TOTAL_MONEY = ? WHERE ID = ?";
$stmt_update_order = $conn->prepare($sql_update_order);
$stmt_update_order->bind_param("di", $current_total_money, $order_id);
$stmt_update_order->execute();

// Đóng kết nối
$stmt->close();
$conn->close();

// Trả về phản hồi JSON
echo json_encode([
    'status' => 'success',
    'message' => $message,
    'cart_total' => $current_total_money,
    'cart_count' => $cartCount
]);
exit();
