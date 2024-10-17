<?php
// Include database connection file
session_start();
include 'auth.php';
include 'db.php';
include('data.php'); // Include the postcodes file

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

// Set the default address and city based on available data
$address = '';
$city = '';
$country = '';

// Initialize total amount
$totalAmount = 0;

// Check if the address contains a comma
if (strpos($userInfo['address'], ',') !== false) {
    // Split the address into parts
    list($address, $city) = explode(',', $userInfo['address'], 2);
    $address = trim($address);
    $city = trim($city);
} else {
    // If there's no comma, assign the entire address to $address
    $address = trim($userInfo['address']);
}

// Kiểm tra và lấy mã bưu chính
$postcode = '';
if (array_key_exists($city, $postcodes)) {
    $postcode = $postcodes[$city];
}

// Retrieve email and phone
$email = $userInfo['email'] ?? '';
$phone = $userInfo['phone'] ?? '';

// Fetch cart items for the user
$cartQuery = "SELECT p.name, od.quantity, p.price 
              FROM orders o 
              JOIN orders_details od ON o.id = od.IDORDER
              JOIN product p ON od.IDPRODUCT = p.id 
              WHERE o.IDUSER = ? AND o.NOTES = 'pending'";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartResult = $stmt->get_result();

// Cập nhật trạng thái đơn hàng từ 'pending' sang 'done'
$sql_update_order = "UPDATE orders SET NOTES = 'done' 
                     WHERE IDUSER = ? AND NOTES = 'pending'";
$stmt = $conn->prepare($sql_update_order);
$stmt->bind_param("i", $userId);
$stmt->execute();

// Kiểm tra xem có bất kỳ trường nào là NULL trong đơn hàng mới nhất
$checkQuery = "SELECT ID 
               FROM orders 
               WHERE IDUSER = ? AND NOTES = 'done' 
               ORDER BY ID DESC LIMIT 1"; // Lấy đơn hàng mới nhất
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$checkResult = $stmt->get_result();
$order = $checkResult->fetch_assoc(); // Lấy dữ liệu của đơn hàng mới nhất

$hasNull = false;
if ($order) {
    // Kiểm tra xem các trường có giá trị NULL không
    $hasNull = is_null($order['ORDERS_DATE']) || is_null($order['NOTES']);
}

// Chỉ tạo đơn hàng mới nếu không có trường nào là NULL trong đơn hàng mới nhất
if (!$hasNull) {
    // Tạo đơn hàng mới với trạng thái 'pending'
    $sql_new_order = "INSERT INTO orders (IDUSER, ORDERS_DATE, NOTES) VALUES (?, NOW(), 'pending')";
    $stmt = $conn->prepare($sql_new_order);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

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
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

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
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links">
                <?php if ($username): ?>
                    <a href="logout.php"><?php echo htmlspecialchars($username); ?></a>
                <?php else: ?>
                    <a href="../malefashion-master/login-male.php">Sign in</a>
                <?php endif; ?>
                <a href="#">FAQs</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
            <a href="#"><img src="img/icon/heart.png" alt=""></a>
            <a href="#"><img src="img/icon/cart.png" alt=""> <span>0</span></a>
            <div class="price">$0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                <?php if ($username): ?>
                                    <a href="logout.php"><?php echo htmlspecialchars($username); ?></a>
                                <?php else: ?>
                                    <a href="../malefashion-master/login-male.php">Sign in</a>
                                <?php endif; ?>
                                <a href="#">FAQs</a>
                            </div>
                            <div class="header__top__hover">
                                <span>Usd <i class="arrow_carrot-down"></i></span>
                                <ul>
                                    <li>USD</li>
                                    <li>EUR</li>
                                    <li>USD</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="./index.php"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li class="active"><a href="./shop.php">Shop</a></li>
                            <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./about.php">About Us</a></li>
                                    <li><a href="./shop-details.php">Shop Details</a></li>
                                    <li><a href="./shopping-cart.php">Shopping Cart</a></li>
                                    <li><a href="./checkout.php">Check Out</a></li>
                                    <li><a href="./blog-details.php">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.php">Blog</a></li>
                            <li><a href="./contact.php">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
                        <a href="#"><img src="img/icon/heart.png" alt=""></a>
                        <a class="shopping-cart" href="shopping-cart.php">
                            <img src="img/icon/cart.png" alt="">
                            <span class="cart-count">0</span>
                        </a>
                        <div class="price total-price">$0.00</div>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form id="checkoutForm">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>First Name<span>*</span></p>
                                        <input type="text" id="firstName" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Last Name<span>*</span></p>
                                        <input type="text" id="lastName" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Country<span>*</span></p>
                                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Street Address" required>
                            </div>
                            <div class="checkout__input">
                                <p>City<span>*</span></p>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" oninput="updatePostcode()" required>
                            </div>
                            <div class="checkout__input">
                                <p>Postcode<span>*</span></p>
                                <input type="text" id="postcode" name="postcode" value="<?php echo htmlspecialchars($postcode); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>
                                <div class="checkout__order__products">Product <span>Total</span></div>
                                <ul class="checkout__total__products">
                                    <?php
                                    while ($cartItem = $cartResult->fetch_assoc()) {
                                        $productTotal = $cartItem['quantity'] * $cartItem['price'];
                                        $totalAmount += $productTotal;
                                        echo "<li>{$cartItem['name']} x {$cartItem['quantity']} <span>$" . number_format($productTotal, 2) . "</span></li>";
                                    }
                                    ?>
                                </ul>
                                <ul class="checkout__total__all">
                                    <li>Subtotal <span>$<?php echo number_format($totalAmount, 2); ?></span></li>
                                    <?php
                                    if (isset($_SESSION['discount'])) {
                                        $discountPercentage = $_SESSION['discount'];
                                        $discountAmount = ($totalAmount * $discountPercentage) / 100;
                                        $totalAfterDiscount = $totalAmount - $discountAmount;
                                        echo "<li>Discount ({$discountPercentage}%) <span>-$" . number_format($discountAmount, 2) . "</span></li>";
                                        echo "<li>Total <span>$" . number_format($totalAfterDiscount, 2) . "</span></li>";
                                    } else {
                                        echo "<li>Total <span>$" . number_format($totalAmount, 2) . "</span></li>";
                                    }
                                    ?>
                                </ul>

                                <button type="button" class="site-btn" data-toggle="modal" data-target="#placeOrderModal" onclick="populateModal()">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
    <!-- Modal for Place Order -->
    <div class="modal fade" id="placeOrderModal" tabindex="-1" role="dialog" aria-labelledby="placeOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="placeOrderLabel">Billing Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form inside the modal -->
                    <form action="checkout.php" method="POST">
                        <h4>Update Saved Payment Method</h4>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="paymentMethod">Select Payment Method<span>*</span></label>
                                <select class="form-control w-100" id="paymentMethod" name="paymentMethod">
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="PayPal">PayPal</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="transportMethod">Select Transport Method<span>*</span></label>
                                <select class="form-control w-100" id="transportMethod" name="transportMethod">
                                    <option value="Standard Delivery">Standard Delivery</option>
                                    <option value="Express Delivery">Express Delivery</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cardNumber">Card Number<span>*</span></label>
                                <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Expiration Date<span>*</span></label>
                                <div class="d-flex">
                                    <div class="col-md-6 pr-1 pl-0">
                                        <input type="text" class="form-control" id="expiryMonth" name="expiryMonth" placeholder="MM" maxlength="2" required>
                                    </div>
                                    <div class="col-md-6 pl-1 pr-0">
                                        <input type="text" class="form-control" id="expiryYear" name="expiryYear" placeholder="YY" maxlength="2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="securityCode">Security Code<span>*</span></label>
                                <input type="text" class="form-control" id="securityCode" name="securityCode" required>
                            </div>
                        </div>
                        <!-- Billing Information section -->
                        <h4>Billing Information</h4>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="modalFirstName">First Name<span>*</span></label>
                                <input type="text" class="form-control" id="modalFirstName" name="modalFirstName" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="modalLastName">Last Name<span>*</span></label>
                                <input type="text" class="form-control" id="modalLastName" name="modalLastName" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="modalCity">City<span>*</span></label>
                                <input type="text" class="form-control" id="modalCity" name="modalCity" oninput="updateModalPostcode()" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="modalAddress">Billing Address<span>*</span></label>
                                <input type="text" class="form-control" id="modalAddress" name="modalAddress" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="modalZip">Zip or Postal Code<span>*</span></label>
                                <input type="text" class="form-control" id="modalZip" name="modalZip" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="modalAddress2">Billing Address, Line 2<span>*</span></label>
                                <input type="text" class="form-control" id="modalAddress2" name="modalAddress2" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="country">Country<span>*</span></label>
                                <select id="country" name="country" class="form-control w-100" aria-label="Default select example">
                                    <option value="VN">Viet Nam</option>
                                    <option value="US">United States</option>
                                    <option value="DE">Germany</option>
                                    <option value="KR">South Korea</option>
                                    <option value="JP">Japan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="modalPhone">Phone Number<span>*</span></label>
                                <input type="text" class="form-control" id="modalPhone" name="modalPhone" required>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="generate_pdf.php" method="POST">
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/footer-logo.png" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="img/payment.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Trending Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Payment Methods</a></li>
                            <li><a href="#">Delivary</a></li>
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
                                <button type="submit"><span class="icon_mail_alt"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                                aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

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

    <script>
        const postcodes = <?php echo json_encode($postcodes); ?>;

        function updatePostcode() {
            const cityInput = document.getElementById('city').value.trim();
            const postcodeInput = document.getElementById('postcode');
            postcodeInput.value = postcodes[cityInput] || '';
        }

        function updateModalPostcode() {
            const cityInput = document.getElementById('modalCity').value.trim();
            const postcodeInput = document.getElementById('modalZip');
            postcodeInput.value = postcodes[cityInput] || '';
        }

        function populateModal() {
            document.getElementById('modalFirstName').value = document.getElementById('firstName').value;
            document.getElementById('modalLastName').value = document.getElementById('lastName').value;
            document.getElementById('modalCity').value = document.getElementById('city').value;
            document.getElementById('modalAddress').value = document.getElementById('address').value;
            document.getElementById('modalZip').value = document.getElementById('postcode').value;
            document.getElementById('modalPhone').value = document.getElementById('phone').value;
            document.getElementById('modalAddress2').value = document.getElementById('address').value;
        }
        document.querySelector('.site-btn').addEventListener('click', populateModal);

        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) {
                value = value.slice(0, 16);
            }
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        document.getElementById('cardNumber').addEventListener('keydown', function(e) {
            if ([8, 9, 37, 39, 46].includes(e.keyCode)) return;
            if (e.key < '0' || e.key > '9') {
                e.preventDefault();
            }
        });

        document.getElementById('expiryMonth').addEventListener('input', function(event) {
            const value = event.target.value.replace(/[^0-9]/g, '');
            if (value.length > 2) {
                event.target.value = value.slice(0, 2);
                return;
            }
            if (value.length === 0) {
                return;
            }
            if (value.length === 1 && value[0] !== '0' && value[0] !== '1') {
                event.target.value = '';
                return;
            }
            if (value.length === 2 && value[0] === '0' && (value[1] < '1' || value[1] > '9')) {
                event.target.value = '0';
                return;
            }
            if (value.length === 2 && value[0] === '1' && (value[1] < '0' || value[1] > '2')) {
                event.target.value = '1';
                return;
            }
            event.target.value = value;
        });

        document.getElementById('expiryYear').addEventListener('keypress', function(event) {
            if (!/[0-9]/.test(event.key)) {
                event.preventDefault();
            }
        });
        document.getElementById('pdfForm').addEventListener('submit', function(event) {
            // Đợi 2 giây trước khi chuyển hướng đến index.php
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000);
        });
    </script>

</body>

</html>