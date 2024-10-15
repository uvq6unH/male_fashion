<?php
session_start(); // Start session

// Check if there are any products in the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize cart if not exists
}

// Logic for adding products to the cart
if (isset($_GET['product_id'])) {
    $productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

    if ($productId === null) {
        die("Product ID is missing.");
    }

    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1; // Ensure quantity is an integer

    // Check if product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity; // Increase quantity
    } else {
        $_SESSION['cart'][$productId] = $quantity; // Add new product
    }
}

// Calculate the total value of the cart
$total = 0;
$cartItems = []; // Initialize cart items array

// Predefined product information for display
$products = [
    1 => ['name' => 'T-shirt Contrast Pocket', 'price' => 98.49, 'image' => 'img/shopping-cart/cart-1.jpg'],
    2 => ['name' => 'Diagonal Textured Cap', 'price' => 98.49, 'image' => 'img/shopping-cart/cart-2.jpg'],
    3 => ['name' => 'Basic Flowing Scarf', 'price' => 98.49, 'image' => 'img/shopping-cart/cart-3.jpg'],
    4 => ['name' => 'Basic Flowing Scarf', 'price' => 98.49, 'image' => 'img/shopping-cart/cart-4.jpg'],
];

// Prepare cart items for display
foreach ($_SESSION['cart'] as $productId => $quantity) {
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $totalPrice = $product['price'] * $quantity;
        $total += $totalPrice; // Add to total
        $cartItems[] = [
            'ID' => $productId,
            'NAME' => $product['name'],
            'PRICE' => $product['price'],
            'QUANTITY' => $quantity,
            'TOTAL' => $totalPrice,
            'IMAGE' => $product['image']
        ];
    }
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
    <title>Male-Fashion | Shop</title>

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
    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cartItems)): ?>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td class="product__cart__item">
                                                <div class="product__cart__item__pic">
                                                    <img src="<?php echo isset($item['IMAGE']) ? $item['IMAGE'] : 'default.jpg'; ?>" alt="">
                                                </div>
                                                <div class="product__cart__item__text">
                                                    <h6><?php echo isset($item['NAME']) ? $item['NAME'] : 'Unknown Product'; ?></h6>
                                                    <h5>$<?php echo number_format($item['PRICE'], 2); ?></h5>
                                                </div>
                                            </td>
                                            <td class="quantity__item">
                                                <div class="quantity">
                                                    <div class="pro-qty-2">
                                                        <input type="text" value="<?php echo isset($item['QUANTITY']) ? $item['QUANTITY'] : '0'; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="cart__price">$<?php echo number_format($item['TOTAL'], 2); ?></td>
                                            <td class="cart__close"><i class="fa fa-close"></i></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">No products in the cart.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn">
                                <a href="shop.php">Continue Shopping</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn update__btn">
                                <a href="#"><i class="fa fa-spinner"></i> Update cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart__discount">
                        <h6>Discount codes</h6>
                        <form action="#">
                            <input type="text" placeholder="Coupon code">
                            <button type="submit">Apply</button>
                        </form>
                    </div>
                    <div class="cart__total">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Subtotal <span>$ <?= number_format($total, 2) ?></span></li>
                            <li>Total <span>$ <?= number_format($total, 2) ?></span></li>
                        </ul>
                        <a href="checkout.php" class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

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