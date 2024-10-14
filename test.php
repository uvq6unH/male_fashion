<?php
@session_start(); // Bắt đầu phiên làm việc

// Khởi tạo biến thông báo
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối cơ sở dữ liệu
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

    $username = $_POST['username'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Kiểm tra các trường không được để trống
    if (empty($username) || empty($password) || empty($address) || empty($email) || empty($phone)) {
        $message = 'Tất cả các trường đều là bắt buộc!';
    } else {
        // Kiểm tra xem tên đăng nhập đã tồn tại chưa
        $checkUsername = $conn->prepare("SELECT * FROM user WHERE USERNAME = ?");
        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();
        $result = $checkUsername->get_result();

        if ($result->num_rows > 0) {
            $message = 'Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.';
        } else {
            // Lưu thông tin người dùng vào cơ sở dữ liệu
            $sql = "INSERT INTO user (USERNAME, PASSWORD, ADDRESS, EMAIL, PHONE) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $username, $password, $address, $email, $phone); // Không hash mật khẩu

            if ($stmt->execute()) {
                $message = 'Đăng ký thành công!';
                // Đặt lại các giá trị input
                $username = $password = $address = $email = $phone = ''; // Làm rỗng các biến
            } else {
                $message = 'Lỗi khi thêm dữ liệu: ' . $stmt->error;
            }

            $stmt->close();
        }

        $checkUsername->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Đăng Ký Tài Khoản</h2>
        <form action="" method="POST" class="mt-4">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" class="form-control" id="username" name="username" value="" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password" value="" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" value="" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng Ký</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        // Kiểm tra xem có thông báo từ PHP không
        <?php
        if (!empty($message)) {
            // Sử dụng JSON để tránh vấn đề về ký tự đặc biệt
            $message_json = json_encode($message);
            echo "Toastify({ text: $message_json, duration: 3000, gravity: 'top', position: 'right', backgroundColor: '#ff4444' }).showToast();";

            // Nếu đăng ký thành công, chuyển hướng sau 2 giây
            if ($message === 'Đăng ký thành công!') {
                echo "setTimeout(function() { window.location.href = 'test_login.php'; }, 500);";
            }
        }
        ?>
    </script>
</body>

</html>