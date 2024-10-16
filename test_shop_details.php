<?php
session_start();
include 'auth.php';
include 'db.php';

// Kiểm tra xem 'id' có được truyền qua URL không
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Truy vấn để lấy thông tin sản phẩm và hình ảnh
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

    // Đóng kết nối
    $stmt->close();
    $conn->close();
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
        <div class="product__details__content">
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
                                    <li><span>Tag:</span> Clothes, Skin, Body</li>
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
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer Reviews(5)</a>
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

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>