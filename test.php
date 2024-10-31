<?php
session_start(); // Khởi động phiên
include "auth.php";
include "db.php"; // Kết nối tới cơ sở dữ liệu

// Lấy sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM product WHERE ISACTIVE = 1";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">Best Sellers</li>
                        <li data-filter=".new-arrivals">New Arrivals</li>
                        <li data-filter=".hot-sales">Hot Sales</li>
                    </ul>
                </div>
            </div>
            <div class="row product__filter">
                <?php foreach ($products as $product) : ?>
                    <?php
                    $classes = [];
                    if ($product['RATING'] == 5) {
                        $classes[] = 'bestsellers';
                    }
                    if ($product['NEWARRIVALS'] == 1) {
                        $classes[] = 'new-arrivals';
                    }
                    if ($product['SALE'] >= 50) {
                        $classes[] = 'hot-sales';
                    }
                    $classString = implode(' ', $classes);
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 mix <?= $classString; ?>">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="img/product/<?php echo htmlspecialchars($product['IMAGE']); ?>">
                                <ul class="product__hover">
                                    <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                    <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                                    <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6><?php echo htmlspecialchars($product['NAME']); ?></h6>
                                <a href="#" class="add-cart">+ Add To Cart</a>
                                <div class="rating">
                                    <?php
                                    $fullStars = floor($product['RATING']);
                                    $halfStar = $product['RATING'] - $fullStars >= 0.5;
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
                                <h5>$<?php echo number_format($product['PRICE'], 2); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

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