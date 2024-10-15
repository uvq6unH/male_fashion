<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "male_fashion";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add products to the cart.");
}

// Lấy ID sản phẩm từ tham số URL
if (!isset($_GET['product_id'])) {
    die("Product ID is missing.");
}
$productId = intval($_GET['product_id']); // Chuyển đổi ID thành số nguyên
$userId = $_SESSION['user_id']; // Lấy ID người dùng từ session

// Kiểm tra xem đã có đơn hàng chưa
$orderId = null;
$orderCheckQuery = "SELECT ID FROM orders WHERE IDUSER = ? AND ORDERS_DATE IS NULL LIMIT 1";
$stmt = $conn->prepare($orderCheckQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu đã có đơn hàng, lấy ID đơn hàng
    $orderRow = $result->fetch_assoc();
    $orderId = $orderRow['ID'];
} else {
    // Nếu không có đơn hàng, tạo đơn hàng mới
    $insertOrderQuery = "INSERT INTO orders (IDUSER, ORDERS_DATE) VALUES (?, NOW())";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $orderId = $stmt->insert_id; // Lấy ID đơn hàng vừa tạo
}

// Lấy giá sản phẩm từ bảng sản phẩm
$productQuery = "SELECT PRICE FROM product WHERE ID = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();
$productResult = $stmt->get_result();

if ($productResult->num_rows > 0) {
    $productRow = $productResult->fetch_assoc();
    $price = $productRow['PRICE'];

    // Thêm sản phẩm vào bảng orders_details
    $insertDetailsQuery = "INSERT INTO orders_details (IDORD, IDPRODUCT, PRICE) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertDetailsQuery);
    $stmt->bind_param("iid", $orderId, $productId, $price);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product added to cart successfully.";
    } else {
        echo "Failed to add product to cart.";
    }
} else {
    echo "Product not found.";
}

// Đóng kết nối
$stmt->close();
$conn->close();
