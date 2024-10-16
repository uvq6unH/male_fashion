<?php
// Include database connection file
include('db.php');
session_start();

// Assuming you have the user ID in the session
$userId = $_SESSION['user_id'];

// Fetch user information
$userQuery = "SELECT name, address, email, phone FROM user WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$userInfo = $userResult->fetch_assoc();

// Split the name into first and last name
list($firstName, $lastName) = explode(' ', $userInfo['name'], 2);

// Set the default address based on available data
$address = $userInfo['address'] ?? '';
$country = $userInfo['address'] ?? '';
$postcode = $userInfo['address'] ?? '';
$email = $userInfo['email'] ?? '';
$phone = $userInfo['phone'] ?? '';

// Fetch cart items for the user
$cartQuery = "SELECT p.name, od.quantity, p.price 
              FROM orders o 
              JOIN orders_details od ON o.id = od.IDORDER
              JOIN product p ON od.IDPRODUCT = p.id 
              WHERE o.IDUSER = ? AND o.NOTES = 'pending'"; // Assuming 'pending' is the status for cart items
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="checkout-process.php" method="POST">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="checkout__input">
                                <p>First Name<span>*</span></p>
                                <input type="text" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Last Name<span>*</span></p>
                                <input type="text" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Country<span>*</span></p>
                                <input type="text" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Street Address" required>
                            </div>
                            <div class="checkout__input">
                                <p>City<span>*</span></p>
                                <input type="text" name="city" value="<?php echo htmlspecialchars($address); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Postcode<span>*</span></p>
                                <input type="text" name="postcode" value="<?php echo htmlspecialchars($postcode); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Phone<span>*</span></p>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Email<span>*</span></p>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>
                                <div class="checkout__order__products">Product <span>Total</span></div>
                                <ul class="checkout__total__products">
                                    <?php
                                    $totalAmount = 0; // Variable to hold total amount
                                    while ($cartItem = $cartResult->fetch_assoc()) {
                                        $productTotal = $cartItem['quantity'] * $cartItem['price'];
                                        $totalAmount += $productTotal; // Calculate the total amount
                                        echo "<li>{$cartItem['name']} x {$cartItem['quantity']} <span>$" . number_format($productTotal, 2) . "</span></li>";
                                    }
                                    ?>
                                </ul>
                                <ul class="checkout__total__all">
                                    <li>Subtotal <span>$<?php echo number_format($totalAmount, 2); ?></span></li>
                                    <li>Total <span>$<?php echo number_format($totalAmount, 2); ?></span></li>
                                </ul>
                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>