<?php
require "../../../config/db.php";
session_start();

if(isset($_SESSION["delete-cart"])){
    echo  "<script>
        window.addEventListener('DOMContentLoaded', function () {
            iziToast.success({
                message: 'محصول با موفقیت حذف شد',
                position: 'topRight',
                timeout: 3000,
                rtl: true
            });
        });
    </script>";
    unset($_SESSION["delete-cart"]);
}

if(isset($_SESSION["send"])){
    echo "<script>
        window.addEventListener('DOMContentLoaded', function () {
            iziToast.success({
                message: 'سفارش شما ثبت شد',
                position: 'topRight',
                timeout: 3000,
                rtl: true
            });
        });
    </script>";
    unset($_SESSION["send"]);
}


if (isset($_SESSION["status"])) {

    $userid = $_SESSION["userid"];

    // select
    $stmt = $conn->prepare("SELECT * FROM tblcart WHERE userID = ? AND paymentStatus = 'pending' ");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_all(MYSQLI_ASSOC);
    

?>

    <!DOCTYPE html>
    <html lang="fa-IR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <!--======================= REMIXICONS =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.min.css">
        <!--======================= css =======================-->
        <link rel="stylesheet" href="../../css/index/index.css">
        <link rel="stylesheet" href="../../css/reset.css">
        <link rel="stylesheet" href="../../css/user/cart.css">
        <!--======================= alert =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
        <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
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
                <i class="ri-shopping-cart-line a2"></i>
                <span class="a2" >سبد خرید</span>
            </a>
            <!-- 4 -->
            <a href="./cart.php" >
                <i class="ri-book-shelf-line"></i>
                <span>دسته بندی</span>
            </a>
            <!-- 5 -->
            <a href="./userpanel.php">
                <i class="ri-user-3-line"></i>
                <span>پروفایل</span>
            </a>
        </div>

    </div>
    <?php
    if(mysqli_num_rows($result) < 1){
        ?><h4 class="er">سبد خرید خالی میباشد !</h4><?php
    }else{
        ?>
        
        <div class="main-list">
            <!-- titles -->
            <h3><i class="ri-menu-5-line"></i>تعداد محصولات  : <?php echo htmlspecialchars(mysqli_num_rows($result)); ?></h3>
            <p>لیست محصولاتی که در سبد شما وجود دارند</p>

            <!-- list -->
            <div class="list">

                <table>

                    <!-- label -->
                    <thead>
                        <tr>
                            <th>تعداد</th>
                            <th>نام محصول</th>
                            <th>تصویر</th>
                            <th>قیمت</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_price = 0;
                        foreach ($cart as $key => $cart2) {

                        // select products
                        $stmt = $conn->prepare("SELECT * FROM tblproducts WHERE productID = ?");
                        $stmt->bind_param("i" , $cart2["productID"]);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $cartp = $result->fetch_assoc();
                            if($cartp){
                            $total_price += $cartp["PriceProduct"] * $cart2["quantity"];
                            
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cart2["quantity"]); ?></td>
                                <td>
                                    <h4><?php echo htmlspecialchars($cartp["productTiTLE"]); ?></h4>
                                </td>
                                <td><img src="../../images/products/<?php echo htmlspecialchars($cartp["ProductImageName"]); ?>" alt="<?php echo htmlspecialchars($cartp["productTiTLE"]); ?>"></td>
                                <td><?php echo htmlspecialchars($cartp["PriceProduct"]); ?></td>
                                <td><a id="openbtn" href="./delete-cart.php?id=<?php echo htmlspecialchars($cartp["productID"]) ?>"><i class="ri-delete-bin-7-line"></i>حذف</a></td>
                            </tr>
                        <?php } } ?>
                    </tbody>
                    <!-- data -->
                </table>

            </div>

     <!--======================= button =======================-->
    <div class="pbox">
        <span>جمع سبد خرید : <?php echo number_format($total_price); ?></span>
        <a href="send.php?id=<?php echo $_SESSION["userid"]; ?>">ثبت سفارش</a>
    </div>


        </div>

   
    </body>
          
    </html>
        
        <?php
    }

} else {
    
    header("location: ./reg.php");
    exit();
}


?>