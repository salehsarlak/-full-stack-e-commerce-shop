<?php

require "../../../config/db.php";
session_start();



if(isset($_SESSION["admin"])){

$stmt = $conn->prepare("SELECT * FROM tblcart WHERE paymentStatus = 'paid' OR  paymentStatus = 'complete' ");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

if(isset($_SESSION["order"])){
    echo  "<script>
        window.addEventListener('DOMContentLoaded', function () {
            iziToast.success({
                message: 'محصول با موفقیت اضافه شد',
                position: 'topRight',
                timeout: 3000,
                rtl: true
            });
        });
    </script>";
        unset($_SESSION["order"]);
    }
}







?>
 <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>لیست سفارشات</title>
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
                <h4>خوش آمدید مدیر عزیز</h4>
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

            <!--======================= table =======================-->
        <div class="main-list">
            <!-- titles -->
            <h3><i class="ri-menu-5-line"></i>تعداد سفارشات سایت : <?php echo htmlspecialchars(mysqli_num_rows($result)); ?></h3>
            <p>لیست سفارشات ثبت شده تا کنون</p>

            <!-- list -->
            <div class="list">

                <table>

                    <!-- label -->
                    <thead>
                        <tr>
                            <th>نام خریدار</th>
                            <th>نام محصول</th>
                            <th>تصویر</th>
                            <th>قیمت</th>
                            <th>وضعیت</th>
                            <th>تغییر وضعیت</th>
                            <th>آدرس</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        ?>
                        
                        <?php foreach ($products as $key => $item) {
                            
                            $stmt = $conn->prepare("SELECT * FROM tblproducts WHERE productID = ? ");

                            $stmt->bind_param("i" , $item["productID"]);
                            $stmt->execute();
                            $ef = $stmt->get_result();
                            $pr = $ef->fetch_assoc();


                            $stmt = $conn->prepare("SELECT * FROM tblcart WHERE productID = ? AND (paymentStatus = 'paid' OR paymentStatus = 'complete') ");
                            $stmt->bind_param("i" , $pr["productID"]);
                            $stmt->execute();
                            $myt = $stmt->get_result();
                            $ecam = $myt->fetch_assoc();


                                // select user
                            $stmt = $conn->prepare("SELECT * FROM tblusers WHERE userid = ?");
                            $stmt->bind_param("i" , $ecam["userID"]);
                            $stmt->execute();
                            $user_result = $stmt->get_result();
                            $user = $user_result->fetch_assoc();
                            ?>
                            
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user["username"]); ?></td>
                                <td>
                                    <h4><?php echo htmlspecialchars($pr["productTiTLE"]); ?></h4>
                                </td>
                                <td><img src="../../images/products/<?php echo htmlspecialchars($pr["ProductImageName"]); ?>" alt="<?php echo htmlspecialchars($pr["productTiTLE"]); ?>"></td>
                                <td><?php echo htmlspecialchars(number_format(($pr["PriceProduct"]))); ?></td>
                                <td><?php if($ecam["paymentStatus"] === 'paid'){echo htmlentities("درحال پردازش");}else{ echo htmlspecialchars("تکمیل شده"); } ?></td>
                                <td><a class="ed"  href="change.php?id=<?php echo htmlspecialchars($ecam["CartID"]); ?>">تغییر</a></td>
                                <td><a href="./address.php?ad=<?php echo $ecam["CartID"]; ?>"><i class="ri-delete-bin-7-line"></i>مشاهده</a></td>
                                
                            </tr>
                        <?php } ?>
                    </tbody>
                    <!-- data -->
                </table>

            </div>
        </div>


        


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


            // delete popup
            const openbtn = document.querySelectorAll("#openbtn")
            // const openbtn = document.getElementById("openbtn")
            const delform = document.getElementById("delform")
            const closebtn = document.getElementById("closebtn")

            openbtn.forEach(function(btn) {
                btn.addEventListener("click", function() {
                    delform.style.display = "block"
                })
            })


            closebtn.addEventListener("click", function() {

                delform.style.display = "none"

            })
        </script>


    </body>
    </html>