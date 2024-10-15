<?php
@session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/Toastify.min.css">
</head>

<body>

    <?php if (isset($_SESSION['username'])): ?>
        <script>
            // Hiển thị thông báo Toast
            Toastify({
                text: "Welcome back, <?php echo $_SESSION['username']; ?>!",
                duration: 3000, // Thời gian hiển thị trong ms
                gravity: "top", // Vị trí hiển thị (top, bottom)
                position: "right", // Vị trí hiển thị (left, center, right)
                backgroundColor: "#4CAF50", // Màu nền
                className: "info",
                stopOnFocus: true // Dừng khi hover
            }).showToast();
        </script>
    <?php else: ?>
        <script>
            // Nếu không có người dùng đăng nhập, chuyển hướng về trang đăng nhập
            window.location.href = "login.php";
        </script>
    <?php endif; ?>

    <!-- Nội dung trang web -->
    <h1>Welcome to the Secret Page!</h1>
    <a href="logout.php">Log out</a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/Toastify.min.js"></script>
</body>

</html>