<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "male_fashion";

    // Tạo kết nối
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    echo "<h1>Kết nối thành công!<br></h1>";

    // Lấy danh sách bảng trong cơ sở dữ liệu
    $sql = "SHOW TABLES";
    $result = $conn->query($sql);

    if ($result) {
        echo "Có các bảng trong cơ sở dữ liệu:<br>";

        // Duyệt qua các bảng trong cơ sở dữ liệu
        while ($row = $result->fetch_array()) {
            $tableName = $row[0];
            echo "Bảng: <strong>" . $tableName . "</strong><br>";

            // Lấy dữ liệu trong bảng
            $dataSql = "SELECT * FROM `$tableName`";
            $dataResult = $conn->query($dataSql);

            if ($dataResult && $dataResult->num_rows > 0) {
                echo "Dữ liệu trong bảng $tableName:<br>";

                // Duyệt qua dữ liệu của bảng
                while ($dataRow = $dataResult->fetch_assoc()) {
                    // Hiển thị dữ liệu theo định dạng dễ đọc hơn
                    echo "<pre>" . htmlspecialchars(print_r($dataRow, true)) . "</pre>";
                }
            } else {
                echo "Bảng $tableName không có dữ liệu.<br>";
            }

            echo "<br>"; // Thêm khoảng cách giữa các bảng
        }
    } else {
        echo "Lỗi truy vấn: " . $conn->error;
    }

    // Đóng kết nối 
    $conn->close();
}
?>

<!-- Form gửi yêu cầu POST -->
<form method="POST" action="">
    <button type="submit">Hiển thị các bảng và dữ liệu</button>
</form>
<h1></h1>