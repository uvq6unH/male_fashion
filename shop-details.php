<?php
session_start();
include 'auth.php';
include 'db.php';

// Kiểm tra xem 'id' có được truyền qua URL không
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Truy vấn để lấy thông tin sản phẩm và hình ảnh
    $sql = "SELECT p.ID, p.NAME, p.IMAGE, p.PRICE, p.DESCRIPTION, p.QUANTITY, p.RATING, c.NAME AS CATEGORY_NAME
        FROM product p
        JOIN category c ON p.IDCATEGORY = c.ID
        WHERE p.ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Sản phẩm không tồn tại.";
        exit();
    }

    // Truy vấn để lấy sản phẩm liên quan cùng danh mục
    $sqlRelated = "SELECT p.ID, p.NAME, p.IMAGE, p.PRICE, p.RATING 
               FROM product p 
               WHERE p.IDCATEGORY = (SELECT IDCATEGORY FROM product WHERE ID = ?) AND p.ID != ? LIMIT 4";
    $stmtRelated = $conn->prepare($sqlRelated);
    $stmtRelated->bind_param("ii", $productId, $productId);
    $stmtRelated->execute();
    $resultRelated = $stmtRelated->get_result();
    $relatedProducts = [];

    if ($resultRelated->num_rows > 0) {
        while ($row = $resultRelated->fetch_assoc()) {
            $relatedProducts[] = $row;
        }
    }

    // Đóng kết nối cho sản phẩm liên quan
    $stmtRelated->close();

    // Đóng kết nối chính nếu không còn sử dụng
    // Chỉ đóng khi bạn đã hoàn tất tất cả các truy vấn
    // $conn->close();
} else {
    echo "ID sản phẩm không hợp lệ.";
    exit();
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
                    <a href="login-male.php">Sign in</a>
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
                                    <div class="dropdown">
                                        <a><?php echo htmlspecialchars($username); ?></a>
                                        <ul class="dropdown-content">
                                            <li><a href="profile.php">Profile</a></li>
                                            <li><a href="logout.php">Logout</a></li>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <a href="login-male.php">Sign in</a>
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
                            <li><a href="">Pages</a>
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

    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Shop</a>
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <div class="product__thumb__pic set-bg" style="background-image: url('img/product/<?= $product['IMAGE']; ?>');"></div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="img/product/<?= $product['IMAGE']; ?>" alt="<?= $product['NAME']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content" data-id="<?= htmlspecialchars($product['ID']); ?>">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4><?= $product['NAME']; ?></h4>
                            <div class="rating">
                                <?php for ($i = 0; $i < floor($product['RATING']); $i++): ?>
                                    <i class="fa fa-star"></i>
                                <?php endfor; ?>
                                <?php if ($product['RATING'] - floor($product['RATING']) >= 0.5): ?>
                                    <i class="fa fa-star-half-o"></i>
                                <?php endif; ?>
                                <span> - <?= number_format($product['RATING']); ?> out of 5</span>
                            </div>
                            <h3>$<?= number_format($product['PRICE'], 2); ?></h3>
                            <p><?= $product['DESCRIPTION']; ?></p>
                            <p>Available Quantity: <?= $product['QUANTITY']; ?></p>
                            <div class="product__details__cart__option">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="text" value="1">
                                    </div>
                                </div>
                                <a href="#" class="primary-btn">add to cart</a>
                            </div>
                            <div class="product__details__btns__option">
                                <a href="#"><i class="fa fa-heart"></i> add to wishlist</a>
                                <a href="#"><i class="fa fa-exchange"></i> Add To Compare</a>
                            </div>
                            <div class="product__details__last__option">
                                <h5><span>Guaranteed Safe Checkout</span></h5>
                                <img src="img/shop-details/details-payment.png" alt="">
                                <ul>
                                    <li><span>SKU:</span> <?= $product['ID']; ?></li>
                                    <li><span>Categories:</span> <?= $product['CATEGORY_NAME']; ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer Reviews</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Shipping & Returns</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <p><?= $product['DESCRIPTION']; ?></p>
                                </div>
                                <div class="tab-pane" id="tabs-6" role="tabpanel">
                                    <div class="product__details__review">
                                        <div class="product__details__review__item">
                                            <div class="product__details__review__item__pic">
                                                <img src="img/product/review-1.jpg" alt="">
                                            </div>
                                            <div class="product__details__review__item__text">
                                                <h6>John Doe</h6>
                                                <span>2022/03/12</span>
                                                <p>Great product! Highly recommend it.</p>
                                            </div>
                                        </div>
                                        <div class="product__details__review__item">
                                            <div class="product__details__review__item__pic">
                                                <img src="img/product/review-2.jpg" alt="">
                                            </div>
                                            <div class="product__details__review__item__text">
                                                <h6>Jane Doe</h6>
                                                <span>2022/03/13</span>
                                                <p>Very satisfied with my purchase.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-7" role="tabpanel">
                                    <p>Please refer to our shipping policy for more information.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <!-- Related Section Begin -->
    <section class="related spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="related-title">Related Products</h3>
                </div>
            </div>
            <div class="row">
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product__item" data-id="<?= htmlspecialchars($related['ID']); ?>"> <!-- Thêm data-id -->
                            <a href="shop-details.php?id=<?= htmlspecialchars($related['ID']); ?>">
                                <div class="product__item__pic set-bg" data-setbg="img/product/<?= htmlspecialchars($related['IMAGE']); ?>">
                                    <ul class="product__hover">
                                        <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                        <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                        <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                                    </ul>
                                </div>
                            </a>
                            <div class="product__item__text">
                                <h6><?= htmlspecialchars($related['NAME']); ?></h6>
                                <a href="#" class="add-cart">+ Add To Cart</a>
                                <div class="rating">
                                    <?php
                                    // Sử dụng RATING từ sản phẩm liên quan
                                    $fullStars = floor($related['RATING']); // Đảm bảo lấy từ $related
                                    $halfStar = $related['RATING'] - $fullStars >= 0.5; // Đảm bảo lấy từ $related
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($i < $fullStars) {
                                            echo '<i class="fa fa-star"></i>';
                                        } elseif ($halfStar && $i == $fullStars) {
                                            echo '<i class="fa fa-star-half-o"></i>';
                                            $halfStar = false;
                                        } else {
                                            echo '<i class="fa fa-star-o"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <h5>$<?= number_format($related['PRICE'], 2); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Related Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-6">
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
                        <h6>Leader</h6>
                        <ul>
                            <li><a href="#">Nguyễn Tuấn Hưng</a></li>
                            <li><a href="#">04-01-2003</a></li>
                            <li><a href="#">21103100251</a></li>
                            <li><a href="#">DHTI15A3HN</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Member</h6>
                        <ul>
                            <li><a href="#">Nguyễn Dương Ninh</a></li>
                            <li><a href="#">04-03-2003</a></li>
                            <li><a href="#">21103100262</a></li>
                            <li><a href="#">DHTI15A3HN</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Member</h6>
                        <ul>
                            <li><a href="#">Nguyễn Anh Huy</a></li>
                            <li><a href="#">12-11-2003</a></li>
                            <li><a href="#">21103100270</a></li>
                            <li><a href="#">DHTI15A3HN</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-6 col-sm-6">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Hàm để gán sự kiện click cho sản phẩm liên quan
            function attachRelatedProductClickEvent() {
                const relatedProductItems = document.querySelectorAll('.related .product__item'); // Chọn tất cả sản phẩm liên quan
                relatedProductItems.forEach(item => {
                    item.addEventListener('click', function() {
                        const productId = this.getAttribute('data-id');
                        if (productId) {
                            window.location.href = 'shop-details.php?id=' + productId; // Chuyển hướng
                        }
                    });
                });
            }
            // Gán sự kiện click cho các sản phẩm liên quan
            attachRelatedProductClickEvent();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hàm xử lý sự kiện "Add to Cart" cho Shop Details
            function handleShopDetailsAddToCart() {
                const shopDetailsButton = document.querySelector('.shop-details .primary-btn'); // Nút "Add to Cart" trong shop-details
                const shopDetailsProductItem = document.querySelector('.shop-details .product__details__content'); // Sản phẩm trong shop-details

                if (shopDetailsButton && shopDetailsProductItem) {
                    shopDetailsButton.addEventListener('click', function(event) {
                        event.preventDefault();

                        const productId = shopDetailsProductItem.getAttribute('data-id');
                        const productPriceText = shopDetailsProductItem.querySelector('h3').innerText; // Lấy giá từ h3
                        const productPrice = parseFloat(productPriceText.replace('$', ''));

                        let totalPrice = parseFloat(document.querySelector('.total-price').innerText.replace('$', ''));
                        let cartCount = parseInt(document.querySelector('.cart-count').innerText);

                        totalPrice += productPrice;
                        cartCount += 1;

                        document.querySelector('.total-price').innerText = '$' + totalPrice.toFixed(2);
                        document.querySelector('.cart-count').innerText = cartCount;

                        fetch('add-to-cart.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `product_id=${productId}&quantity=1`
                            })
                            .then(response => response.text())
                            .then(data => {
                                console.log('Item added to cart:', data);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                }
            }

            // Hàm xử lý sự kiện "Add to Cart" cho Related Products
            function handleRelatedAddToCart() {
                const relatedAddToCartButtons = document.querySelectorAll('.related .add-cart'); // Tất cả nút "Add to Cart" trong related section

                relatedAddToCartButtons.forEach(button => {
                    const relatedProductItem = button.closest('.product__item'); // Lấy phần tử cha sản phẩm liên quan

                    button.addEventListener('click', function(event) {
                        event.preventDefault();

                        const productId = relatedProductItem.getAttribute('data-id');
                        const productPriceText = relatedProductItem.querySelector('h5').innerText; // Lấy giá từ h3
                        const productPrice = parseFloat(productPriceText.replace('$', ''));

                        let totalPrice = parseFloat(document.querySelector('.total-price').innerText.replace('$', ''));
                        let cartCount = parseInt(document.querySelector('.cart-count').innerText);

                        totalPrice += productPrice;
                        cartCount += 1;

                        document.querySelector('.total-price').innerText = '$' + totalPrice.toFixed(2);
                        document.querySelector('.cart-count').innerText = cartCount;

                        fetch('add-to-cart.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `product_id=${productId}&quantity=1`
                            })
                            .then(response => response.text())
                            .then(data => {
                                console.log('Item added to cart:', data);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });
            }

            // Gán sự kiện cho Shop Details
            handleShopDetailsAddToCart();

            // Gán sự kiện cho Related Products
            handleRelatedAddToCart();
        });
    </script>
</body>

</html>