<?php

require "../../../config/db.php";
session_start();


if (isset($_SESSION["admin"])) {

    
$stmt = $conn->prepare("SELECT * FROM tblcart WHERE paymentStatus = 'paid' OR  paymentStatus = 'complete' ");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_assoc();


// selcet users

        $stmt = $conn->prepare("SELECT * FROM tblusers");

        $stmt->execute();
        $result_user = $stmt->get_result();
        $user = $result_user->fetch_assoc();

        // select products
            $stmt = $conn->prepare("SELECT * FROM tblproducts ORDER BY productID DESC");
    $stmt->execute();
    $result_product = $stmt->get_result();
    $product = $result_product->fetch_all(MYSQLI_ASSOC);


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>پنل مدیزیت</title>
        <!--======================= REMIXICONS =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.min.css">
        <!--======================= css =======================-->
        <link rel="stylesheet" href="../../css/admin-panel/admin-panel.css">
        <link rel="stylesheet" href="../../css/reset.css">
        <!--======================= alert =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">

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

        <div class="result">
            <h3>پنل مدیریت بازارون</h3>


              <div class="main-list">
            <!-- titles -->
            <h3><i class="ri-menu-5-line"></i>تعداد سفارشات سایت : <?php echo htmlspecialchars($result->num_rows); ?></h3>
            

            <!-- list -->
            <div class="list">

                <table>

                    <!-- label -->
                    <thead>
                        <tr>
                            <th>تعداد کاربران</th>
                            <th>تعداد محصولات</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        ?>
                        
                        
                            
                            <tr>
                                <td><?php echo htmlspecialchars($result_user->num_rows); ?></td>
                                <td><?php echo htmlspecialchars($result_product->num_rows); ?></td>    
                            </tr>

                    </tbody>
                    <!-- data -->
                </table>

            </div>
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
        </script>
    </body>

    </html>


<?php 
}else{
    header("location: ./admin-login.php");
    exit();
}