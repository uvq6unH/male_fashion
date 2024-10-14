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

// Khởi tạo bộ lọc danh mục rỗng
$categoryFilter = '';

// Kiểm tra xem danh mục có được thiết lập trong chuỗi truy vấn không
if (isset($_GET['category'])) {
    $categoryFilter = $conn->real_escape_string($_GET['category']);
}

$sql = "SELECT p.ID, p.NAME, p.IMAGE, p.PRICE, c.NAME AS CATEGORY_NAME 
        FROM product p 
        JOIN category c ON p.IDCATEGORY = c.ID 
        WHERE p.ISACTIVE = 1";

if (!empty($categoryFilter)) {
    $sql .= " AND c.NAME = '$categoryFilter'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

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
    <div class="card">
        <div class="card-heading">
            <a data-toggle="collapse" data-target="#collapseSix">Tags</a>
        </div>
        <div id="collapseSix" class="collapse show" data-parent="#accordionExample">
            <div class="card-body">
                <div class="shop__sidebar__tags">
                    <a class="category-link" href="?category=Product">Product</a>
                    <a class="category-link" href="?category=Bags">Bags</a>
                    <a class="category-link" href="?category=Shoes">Shoes</a>
                    <a class="category-link" href="?category=Fashion">Fashion</a>
                    <a class="category-link" href="?category=Clothing">Clothing</a>
                    <a class="category-link" href="?category=Hats">Hats</a>
                    <a class="category-link" href="?category=Accessories">Accessories</a>
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
                echo "<div class='product__color__select'>";
                echo "<label for='pc-4'><input type='radio' id='pc-4'></label>";
                echo "<label class='active black' for='pc-5'><input type='radio' id='pc-5'></label>";
                echo "<label class='grey' for='pc-6'><input type='radio' id='pc-6'></label>";
                echo "</div>";
                echo "</div>"; // end product__item__text
                echo "</div>"; // end product__item
                echo "</div>"; // end col-lg-4 col-md-6 col-sm-6
            }
        } else {
            echo "<p>No products found.</p>";
        }

        $conn->close();
        ?>
    </div>
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