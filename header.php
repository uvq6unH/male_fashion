<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include the auth logic
include 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Link to your CSS -->
    <title>Your Website Title</title>
</head>

<body>
    <header>
        <div class="header__top__links">
            <?php if ($username): ?>
                <a href="logout.php"><?php echo htmlspecialchars($username); ?></a>
            <?php else: ?>
                <?php if ($username): ?>
                    <a href="logout.php"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="../malefashion-master/login-male.php">Sign in</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="#">FAQs</a>
        </div>
    </header>