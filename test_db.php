<?php
include 'db.php'; // Gọi file db.php

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

echo "Kết nối thành công!<br>";

// Lấy danh sách bảng trong cơ sở dữ liệu
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result) {
    echo "Có các bảng trong cơ sở dữ liệu:<br>";
    while ($row = $result->fetch_array()) {
        $tableName = $row[0];
        echo "Bảng: " . $tableName . "<br>";

        // Lấy dữ liệu trong bảng
        $dataSql = "SELECT * FROM `$tableName`";
        $dataResult = $conn->query($dataSql);

        if ($dataResult && $dataResult->num_rows > 0) {
            echo "Dữ liệu trong bảng $tableName:<br>";
            while ($dataRow = $dataResult->fetch_assoc()) {
                echo "<pre>" . print_r($dataRow, true) . "</pre>"; // In ra dữ liệu
            }
        } else {
            echo "Bảng $tableName không có dữ liệu.<br>";
        }

        echo "<br>"; // Thêm khoảng cách giữa các bảng
    }
} else {
    echo "Lỗi truy vấn: " . $conn->error;
}

$conn->close(); // Đóng kết nối 