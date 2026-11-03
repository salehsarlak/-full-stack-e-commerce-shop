<?php

require "../../../config/db.php";
session_start();

if(isset($_SESSION["admin"]) && isset($_GET["ad"]) && is_numeric($_GET["ad"])){

    $stmt = $conn->prepare("SELECT * FROM tblcart WHERE CartID = ?");
    $stmt->bind_param("i" , $_GET["ad"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $address = $result->fetch_assoc();

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>خوش آمدید</title>
        <!--======================= REMIXICONS =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.min.css">
        <!--======================= css =======================-->
        <link rel="stylesheet" href="../../css/admin-panel/admin-panel.css">
        <link rel="stylesheet" href="../../css/reset.css">
        <!--======================= alert =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
        <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

    </head>

    <body>
        <!--======================= header =======================-->

        <div class="main-header">
            <!-- content -->
            <div class="content">
                <a href="../../../index.php"><img src="../../images/logo2.webp" alt=""></a>
                <i class="ri-menu-line menu"></i>
                <i class="ri-close-fill close"></i>
                <!-- title -->
                <h4>آدرس سفارش</h4>
                <!-- menu -->
                <ul>
                    <li><a href="../../../"><i class="ri-home-6-line"></i>خانه</a></li>
                    <li><a href="./admin-panel.php"><i class="ri-wrench-line"></i>پیشخوان</a></li>
                    <li><a href="./addproduct.php"><i class="ri-add-fill"></i>افزودن محصول</a></li>
                    <li><a href="./products.php"><i class="ri-menu-5-fill"></i>لیست محصولات</a></li>
                    <li><a href="./users.php"><i class="ri-group-line"></i>لیست کاربران</a></li>
                    <li><a href="./orders.php"><i class="ri-file-list-3-line"></i>لیست سفارشات</a></li>
                    <li><a href="./logout.php">خروج</a></li>
                </ul>
            </div>
        </div>

        <div class="main-address">
            <p>آدرس سفارش مشتری :</p>
            <h4><?php echo htmlspecialchars($address["address"]); ?></h4>
            <a href="./orders.php">باگشت به لیست</a>
        </div>

    </body>
    <script>
            const menu = document.querySelector(".main-header .content >ul")
            const button = document.querySelector(".main-header .content >i")
            const close = document.querySelector(".main-header .content >.close")
            button.addEventListener('click', function() {

                menu.style.display = 'flex';
                button.style.display = 'none';
                close.style.display = 'block';

            })

            close.addEventListener('click', function() {

                menu.style.display = 'none';
                close.style.display = 'none';
                button.style.display = 'block';


            })
</script>
    </html>

<?php
}else{
    header("location: ./admin-login.php");
    exit();
    
}

?>