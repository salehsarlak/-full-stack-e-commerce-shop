<?php


require "../../../config/db.php";
session_start();

// check login 

if (!isset($_SESSION["status"]) || $_SESSION["status"] !== true || !isset($_SESSION["userid"])) {
    header("location: ./reg.php");

    if (isset($_SESSION["admin"])) {
        header("location: ../admin/admin-panel.php");
        exit();
    }
}

$stmt = $conn->prepare("SELECT * FROM tblusers WHERE userid = ?");
$stmt->bind_param("i" , $_SESSION["userid"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!--======================= REMIXICONS =======================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.min.css">
    <!--======================= css =======================-->
    <link rel="stylesheet" href="../../css/index/index.css">
    <link rel="stylesheet" href="../../css/reset.css">
    <!--======================= alert =======================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <!--======================= script =======================-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
</head>

<body>
    <!DOCTYPE html>
<html lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!--======================= REMIXICONS =======================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.min.css">
    <!--======================= css =======================-->
    <link rel="stylesheet" href="./assets/css/index/index.css">
    <link rel="stylesheet" href="./assets/css/reset.css">
</head>

<body>
    <!--======================= header =======================-->
    <header class="main-header">
        <div class="content">
            <!-- d1 -->
            <div class="d1">

                <img src="../../images/logo2.webp" alt="">
                <div class="searchdiv">
                    <input placeholder="جستجو در بازارون" type="search">
                    <i class="ri-search-2-line"></i>
                </div>

            </div>
            <!-- d2 -->
            <div class="d2">
                <a href="./cart.php">سبد خرید<i class="ri-shopping-bag-3-fill"></i></a>
                <a href="./userpanel.php">حساب کاربری<i class="ri-user-smile-line"></i></a>
            </div>
        </div>
        <!-- div3 -->
        <nav class="div3">
            <ul>
                <?php
                if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) { ?> <li><a href="../admin/admin-panel.php">پنل مدیریت</a></li> <?php } else {
                                                                                                                                                        ?> <li><a href="../../../index.php">صفحه اصلی</a></li><?php
                                                                                                                                                                                        } ?>
                <li><a href="../../../index.php">همه محصولات</a></li>
                <li><a href="../../../pages/about.css">درباره ما</a></li>
                <li><a href="../../../pages/contact.css">تماس با ما</a></li>
            </ul>
        </nav>





    </header>

    <!--======================= navbar =======================-->
    <div class="navbar">
        <div class="navcontent">
            <!-- 1 -->
            <a href="../../../index.php">
                <i class="ri-home-smile-fill"></i>
                <span>خانه</span>
            </a>
            <!-- 2 -->
            <a >
                <i class="ri-movie-2-line"></i>
                <span>اکسپلور</span>
            </a>
            <!-- 3 -->
            <a href="./cart.php" >
                <i class="ri-shopping-cart-line"></i>
                <span>سبد خرید</span>
            </a>
            <!-- 4 -->
            <a href="./cart.php" >
                <i class="ri-book-shelf-line"></i>
                <span>دسته بندی</span>
            </a>
            <!-- 5 -->
            <a href="./userpanel.php">
                <i class="ri-user-3-line a2"></i>
                <span class="a2" >پروفایل</span>
            </a>
        </div>

    </div>

    <div class="user">
        <h3>خوش آمدید <?php echo htmlspecialchars($user["username"]); ?> عزیز </h3>
         <a href="./cart.php">سبد خرید شما</a>
    </div>
</body>

</html>
<!-- alert -->
<?php
if (isset($_SESSION["status"])) {

?> <script src="../assets/js/alert.js"></script>
<?php

}
?>
<!--  -->











</body>

</html>