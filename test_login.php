<?php
@session_start(); // Bắt đầu phiên làm việc
// Khởi tạo biến thông báo
$message = '';
$redirect = false; // Biến để kiểm tra có chuyển hướng hay không

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

    // Lấy thông tin từ form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Sử dụng Prepared Statement để tránh SQL Injection
    $stmt = $conn->prepare("SELECT USERNAME, PASSWORD FROM user WHERE USERNAME = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra và xử lý kết quả
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // So sánh mật khẩu nhập vào với mật khẩu từ cơ sở dữ liệu
        if ($password === $row['PASSWORD']) { // Không sử dụng hash mật khẩu
            // Đăng nhập thành công
            $_SESSION['username'] = $row['USERNAME'];
            $redirect = true; // Đánh dấu cần chuyển hướng
        } else {
            // Mật khẩu không đúng
            $message = "Tài khoản hoặc mật khẩu không đúng!";
        }
    } else {
        // Không tìm thấy tài khoản
        $message = "Không tìm thấy tài khoản!";
    }

    // Đóng kết nối nếu đã mở
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

</head>

<body>
    <div class="container">
        <h2 class="mt-5">Đăng Nhập Tài Khoản</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="mt-4">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        </form>
    </div>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Kiểm tra xem có cần chuyển hướng không
        <?php if ($redirect): ?>
            // Hiển thị thông báo đăng nhập thành công
            Toastify({
                text: "Đăng nhập thành công!",
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#28a745', // Màu xanh cho thành công
            }).showToast();

            // Chuyển hướng sau 2 giây
            setTimeout(function() {
                window.location.href = "secret.php"; // Thay đổi URL này nếu cần
            }, 2000);
        <?php endif; ?>
    </script>
</body>

</html>