<?php

require "../../../config/db.php";
session_start();


if (isset($_SESSION["admin"])) {

    // notification
    if (isset($_SESSION["add_product"])) {
        echo "<script>
        window.addEventListener('DOMContentLoaded', function () {
            iziToast.success({
                message: 'محصول با موفقیت اضافه شد',
                position: 'topRight',
                timeout: 3000,
                rtl: true
            });
        });
    </script>";
        unset($_SESSION["add_product"]);
    }

    // edit notification
    if (isset($_SESSION["edit_product"])) {
        echo "<script>
        window.addEventListener('DOMContentLoaded', function () {
            iziToast.success({
                message: 'محصول با موفقیت ویرایش شد',
                position: 'topRight',
                timeout: 3000,
                rtl: true
            });
        });
    </script>";
        unset($_SESSION["edit_product"]);
    }

    // delete notification
    if (isset($_SESSION["delete"])) {
        echo "<script>
    window.addEventListener('DOMContentLoaded', function () {
        iziToast.show({
            message: 'محصول مورد نظر حذف شد',
            position: 'topRight',
            timeout: 3000,
            rtl: true,
            color: 'red',
            icon: 'ri-delete-bin-7-line'
        });
    });
</script>";
        unset($_SESSION["delete"]);
    }


    // import products

    $stmt = $conn->prepare("SELECT * FROM tblproducts ORDER BY productID DESC");
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_all(MYSQLI_ASSOC);




?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>لیست محصولات</title>
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
            <h3><i class="ri-menu-5-line"></i>تعدا د محصولات سایت : <?php echo htmlspecialchars(mysqli_num_rows($result)); ?></h3>
            <p>لیست محصولاتی که در سایت وجود دارند</p>

            <!-- list -->
            <div class="list">

                <table>

                    <!-- label -->
                    <thead>
                        <tr>
                            <th>آِیدی</th>
                            <th>نام محصول</th>
                            <th>تصویر</th>
                            <th>قیمت</th>
                            <th>ویرایش محصول</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product as $key => $item) {


                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item["productID"]); ?></td>
                                <td>
                                    <h4><?php echo htmlspecialchars($item["productTiTLE"]); ?></h4>
                                </td>
                                <td><img src="../../images/products/<?php echo htmlspecialchars($item["ProductImageName"]); ?>" alt="<?php echo htmlspecialchars($item["productTiTLE"]); ?>"></td>
                                <td><?php echo htmlspecialchars($item["PriceProduct"]); ?></td>
                                <td><a class="ed" href="./edit.php?id=<?php echo htmlspecialchars($item["productID"]); ?>" data-id="<?php echo ($item["productID"]) ?>" id="edit"><i class="ri-pencil-line"></i>ویرایش</a></td>
                                <td><button id="openbtn" href=""><i class="ri-delete-bin-7-line"></i>حذف</button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <!-- data -->
                </table>

            </div>
        </div>

        <!-- popup -->
        <div id="delform" class="main-delete">
            <div>
                <span>آیا محصول حذف شود؟</span>
                <p>پس از کلیک روی حذف محصول دیگر قابل بازیابی نیست </p>
                <a href="delete.php?id=<?php echo htmlspecialchars($item["productID"]); ?>">حذف</a>
                <button id="closebtn">بیخیال</button>

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

<?php } else {
    header("location: ./admin-login.php");
    exit();
}





?>