<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "male_fashion";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Khởi tạo biến bộ lọc danh mục và điều kiện sắp xếp
$categoryFilter = '';
$priceFilter = '';
$priceCondition = ''; // Khởi tạo biến $priceCondition
$sortOrder = 'ASC'; // Mặc định sắp xếp từ thấp đến cao
$sortOption = 'low-high'; // Khởi tạo biến $sortOption
$itemsPerPage = 12; // Số sản phẩm hiển thị mỗi trang
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy trang hiện tại từ URL
$searchQuery = ''; // Khởi tạo biến tìm kiếm

// Xử lý tìm kiếm
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
}

// Kiểm tra xem danh mục có được thiết lập không
if (isset($_GET['category'])) {
    $categoryFilter = $conn->real_escape_string($_GET['category']);
}

// Kiểm tra xem có lựa chọn sắp xếp giá không
if (isset($_GET['sort'])) {
    $sortOption = $_GET['sort'];

    if ($sortOption == "low-high") {
        $sortOrder = "ASC";
    } elseif ($sortOption == "high-low") {
        $sortOrder = "DESC";
    }
}

// Kiểm tra xem có điều kiện giá nào không
if (isset($_GET['price'])) {
    $priceFilter = $_GET['price'];
    // Xóa ký tự '$' nếu có và trim khoảng trắng
    $priceFilter = str_replace('$', '', trim($priceFilter));

    // Kiểm tra xem có dấu '+' không
    if (strpos($priceFilter, '+') !== false) {
        // Xử lý trường hợp giá lớn hơn một số nhất định
        $minPrice = floatval(trim(str_replace('+', '', $priceFilter))); // Loại bỏ dấu '+'
        $priceCondition = "p.PRICE >= $minPrice"; // Điều kiện cho giá lớn hơn hoặc bằng
    } else {
        // Tạo điều kiện lọc giá
        $priceRange = explode(" - ", $priceFilter);

        if (count($priceRange) === 2) {
            // Điều kiện lọc cho khoảng giá
            $minPrice = floatval(trim($priceRange[0]));
            $maxPrice = floatval(trim($priceRange[1]));
            $priceCondition = "p.PRICE BETWEEN $minPrice AND $maxPrice";
        } else {
            // Xử lý trường hợp chỉ có giá tối thiểu
            $minPrice = floatval(trim($priceRange[0]));
            $priceCondition = "p.PRICE >= $minPrice"; // Nếu chỉ có giá tối thiểu
        }
    }
}

// Truy vấn SQL để đếm tổng số sản phẩm
$countQuery = "SELECT COUNT(*) AS total FROM product p JOIN category c ON p.IDCATEGORY = c.ID WHERE p.ISACTIVE = 1";

// Thêm điều kiện lọc danh mục nếu được chọn
if (!empty($categoryFilter)) {
    $countQuery .= " AND c.NAME = '$categoryFilter'";
}
// Thêm điều kiện lọc giá nếu được chọn
if (!empty($priceCondition)) {
    $countQuery .= " AND $priceCondition";
}
// Thêm điều kiện tìm kiếm
if (!empty($searchQuery)) {
    $searchQuery = $conn->real_escape_string($searchQuery);
    $countQuery .= " AND p.NAME LIKE '%$searchQuery%'";
}

$countResult = $conn->query($countQuery);
$totalProducts = $countResult->fetch_assoc()['total'];

// Tính toán số trang
$totalPages = ceil($totalProducts / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;

// Truy vấn SQL để lấy dữ liệu sản phẩm với bộ lọc danh mục và sắp xếp giá
$sql = "SELECT p.ID, p.NAME, p.IMAGE, p.PRICE, c.NAME AS CATEGORY_NAME 
        FROM product p 
        JOIN category c ON p.IDCATEGORY = c.ID 
        WHERE p.ISACTIVE = 1";

// Thêm điều kiện lọc danh mục nếu được chọn
if (!empty($categoryFilter)) {
    $sql .= " AND c.NAME = '$categoryFilter'";
}

// Thêm điều kiện lọc giá nếu được chọn
if (!empty($priceCondition)) {
    $sql .= " AND $priceCondition";
}

// Thêm điều kiện tìm kiếm
if (!empty($searchQuery)) {
    $sql .= " AND p.NAME LIKE '%$searchQuery%'";
}

// Thêm điều kiện sắp xếp theo giá
$sql .= " ORDER BY p.PRICE $sortOrder LIMIT $offset, $itemsPerPage"; // Thêm LIMIT để phân trang

// Thực hiện truy vấn
$result = $conn->query($sql);
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
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links">
                <a href="../malefashion-master/login-male.php">Sign in</a>
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
                                <a href="../malefashion-master/login-male.php">Sign in</a>
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
                        <a href="#"><img src="img/icon/cart.png" alt=""> <span>0</span></a>
                        <div class="price">$0.00</div>
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

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form action="" method="GET"> <!-- Gửi dữ liệu tìm kiếm qua phương thức GET -->
                                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll">
                                                    <li><a class="category-link" href="?category=Product&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Product</a></li>
                                                    <li><a class="category-link" href="?category=Bags&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Bags</a></li>
                                                    <li><a class="category-link" href="?category=Shoes&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Shoes</a></li>
                                                    <li><a class="category-link" href="?category=Fashion&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Fashion</a></li>
                                                    <li><a class="category-link" href="?category=Clothing&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Clothing</a></li>
                                                    <li><a class="category-link" href="?category=Hats&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Hats</a></li>
                                                    <li><a class="category-link" href="?category=Accessories&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Accessories</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseTwo">Branding</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__brand">
                                                <ul>
                                                    <li><a href="#">Louis Vuitton</a></li>
                                                    <li><a href="#">Chanel</a></li>
                                                    <li><a href="#">Hermes</a></li>
                                                    <li><a href="#">Gucci</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                                    </div>
                                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__price">
                                                <ul>
                                                    <li><a href="?price=$0.00 - $50.00&category=<?php echo htmlspecialchars($categoryFilter); ?>&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">$0.00 - $50.00</a></li>
                                                    <li><a href="?price=$50.00 - $100.00&category=<?php echo htmlspecialchars($categoryFilter); ?>&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">$50.00 - $100.00</a></li>
                                                    <li><a href="?price=$100.00 - $150.00&category=<?php echo htmlspecialchars($categoryFilter); ?>&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">$100.00 - $150.00</a></li>
                                                    <li><a href="?price=$150.00 - $200.00&category=<?php echo htmlspecialchars($categoryFilter); ?>&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">$150.00 - $200.00</a></li>
                                                    <li><a href="?price=$200.00 - $250.00&category=<?php echo htmlspecialchars($categoryFilter); ?>&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">$200.00 - $250.00</a></li>
                                                    <li><a href="?price=$250.00+&category=<?php echo htmlspecialchars($categoryFilter); ?>&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">$250.00+</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseFour">Size</a>
                                    </div>
                                    <div id="collapseFour" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__size">
                                                <label for="xs">xs
                                                    <input type="radio" id="xs">
                                                </label>
                                                <label for="sm">s
                                                    <input type="radio" id="sm">
                                                </label>
                                                <label for="md">m
                                                    <input type="radio" id="md">
                                                </label>
                                                <label for="xl">xl
                                                    <input type="radio" id="xl">
                                                </label>
                                                <label for="2xl">2xl
                                                    <input type="radio" id="2xl">
                                                </label>
                                                <label for="xxl">xxl
                                                    <input type="radio" id="xxl">
                                                </label>
                                                <label for="3xl">3xl
                                                    <input type="radio" id="3xl">
                                                </label>
                                                <label for="4xl">4xl
                                                    <input type="radio" id="4xl">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseFive">Colors</a>
                                    </div>
                                    <div id="collapseFive" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__color">
                                                <label class="c-1" for="sp-1">
                                                    <input type="radio" id="sp-1">
                                                </label>
                                                <label class="c-2" for="sp-2">
                                                    <input type="radio" id="sp-2">
                                                </label>
                                                <label class="c-3" for="sp-3">
                                                    <input type="radio" id="sp-3">
                                                </label>
                                                <label class="c-4" for="sp-4">
                                                    <input type="radio" id="sp-4">
                                                </label>
                                                <label class="c-5" for="sp-5">
                                                    <input type="radio" id="sp-5">
                                                </label>
                                                <label class="c-6" for="sp-6">
                                                    <input type="radio" id="sp-6">
                                                </label>
                                                <label class="c-7" for="sp-7">
                                                    <input type="radio" id="sp-7">
                                                </label>
                                                <label class="c-8" for="sp-8">
                                                    <input type="radio" id="sp-8">
                                                </label>
                                                <label class="c-9" for="sp-9">
                                                    <input type="radio" id="sp-9">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseSix">Tags</a>
                                    </div>
                                    <div id="collapseSix" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__tags">
                                                <a class="category-link" href="?category=Product&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Product</a>
                                                <a class="category-link" href="?category=Bags&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Bags</a>
                                                <a class="category-link" href="?category=Shoes&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Shoes</a>
                                                <a class="category-link" href="?category=Fashion&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Fashion</a>
                                                <a class="category-link" href="?category=Clothing&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Clothing</a>
                                                <a class="category-link" href="?category=Hats&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Hats</a>
                                                <a class="category-link" href="?category=Accessories&sort=<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'low-high'; ?>">Accessories</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing <?php echo min($itemsPerPage, $totalProducts - $offset); ?> of <?php echo $totalProducts; ?> results</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <form method="GET">
                                        <p>Sort by Price:</p>
                                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($priceFilter); ?>">
                                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>">
                                        <select name="sort" onchange="this.form.submit()">
                                            <option value="low-high" <?php echo $sortOption == 'low-high' ? 'selected' : ''; ?>>Low to High</option>
                                            <option value="high-low" <?php echo $sortOption == 'high-low' ? 'selected' : ''; ?>>High to Low</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="product-results" class="row">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $imagePath = 'img/product/' . $row["IMAGE"];
                                echo "<div class='col-lg-4 col-md-6 col-sm-6'>";
                                echo "<div class='product__item'>";
                                echo "<div class='product__item__pic set-bg' data-setbg='$imagePath'>";
                                echo "<ul class='product__hover'>";
                                echo "<li><a href='#'><img src='img/icon/heart.png' alt=''></a></li>";
                                echo "<li><a href='#'><img src='img/icon/compare.png' alt=''> <span>Compare</span></a></li>";
                                echo "<li><a href='#'><img src='img/icon/search.png' alt=''></a></li>";
                                echo "</ul>";
                                echo "</div>";
                                echo "<div class='product__item__text'>";
                                echo "<h6>" . $row["NAME"] . "</h6>";
                                echo "<a href='#' class='add-cart'>+ Add To Cart</a>";
                                echo "<div class='rating'>";
                                for ($i = 0; $i < 5; $i++) {
                                    echo "<i class='fa fa-star-o'></i>";
                                }
                                echo "</div>";
                                echo "<h5>$" . number_format($row["PRICE"], 2) . "</h5>";
                                echo "</div></div></div>";
                            }
                        } else {
                            echo "<p>No products found.</p>";
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__pagination">
                                <?php
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($i == $currentPage) {
                                        echo "<a class='active' href='?page=$i&category=" . urlencode($categoryFilter) . "&sort=$sortOption'>$i</a>";
                                    } else {
                                        echo "<a href='?page=$i&category=" . urlencode($categoryFilter) . "&sort=$sortOption'>$i</a>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->

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
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
?>