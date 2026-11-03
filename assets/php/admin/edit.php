<?php
require "../../../config/db.php";
session_start();







if (isset($_SESSION["admin"])) {

    $stmt = $conn->prepare("SELECT * FROM tblproducts WHERE productID = ? ");
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $md = $result->fetch_assoc();




    $title = "";
    $image = "";
    $price = "";

    $titleErr = "";
    $imageErr = "";
    $priceErr = "";
    $importErr = "";
    $create = "";

    // image formats

    $required_formats = array("image/png", "image/webp",  "image/jpeg");

    function test_input($data)
    {

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["send"])) {

            if (empty($_POST["title"])) {

                $titleErr = "* نام محصول نمیتواند خالی باشد.";
            } else {
                $title = test_input($_POST["title"]);
            }


            if (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {

                if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
                    $imageErr = "* آپلود تصویر با خطا مواجه شد.";
                } elseif (!in_array($_FILES["image"]["type"], $required_formats)) {
                    $imageErr = "* فرمت‌های مجاز: png، webp و jpeg";
                } else {
                    $dir = "../../images/products/";
                    $filename = basename($_FILES["image"]["name"]);

                    $targetfile = $dir . $filename;

                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetfile)) {
                        $image = $filename;
                    }
                }
            }






            if (empty($_POST["price"])) {

                $priceErr = "* لطفا قیمت را وارد کنید.";
            } elseif (!is_numeric($_POST["price"]) || (float) $_POST["price"] <= 0) {
                $priceErr = "* قیمت محصول باید بیشتر از صفر باشد";
            } else {
                $price = test_input($_POST["price"]);
            }

            // import products
            if ($titleErr === "" && $imageErr === "" && $priceErr === "") {

                if ($image !== "") {
                    $stmt = $conn->prepare("UPDATE tblproducts SET productTiTLE = ? , PriceProduct = ? , ProductImageName = ? WHERE productID = ?");
                    $stmt->bind_param("sssi", $title, $price, $image,  $_GET["id"]);
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("UPDATE tblproducts SET productTiTLE = ? , PriceProduct = ?   WHERE productID = ?");
                    $stmt->bind_param("ssi", $title, $price,  $_GET["id"]);
                    $stmt->execute();
                }

                $_SESSION["edit_product"] = true;
                header("Location: ../admin/products.php");
                exit();
            }
        }
    }












?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ویرایش محصول</title>
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

        <!--======================= add products form =======================-->
        <div class="main-add">
            <h3><i class="ri-store-2-line"></i>فرم ویرایش محصول</h3>
            <p>صفحه ویرایش محصول بازارون</p>


            <!-- form -->

            <div class="form-wrapper">
                <form enctype="multipart/form-data" method="POST" action="">
                    <!-- title -->
                    <div class="inp">
                        <label for="p-title">نام محصول</label>
                        <input name="title" id="p-title" type="text">
                        <span class="err"><?php echo $titleErr ?></span>
                    </div>
                    <!-- upload image -->
                    <div class="inp">

                        <label for="">تصویر محصول</label>
                        <div class="up"><span><i class="ri-image-upload-line"></i>آپلود تصویر</span><input name="image" type="file"></div>
                        <span class="err"><?php echo $imageErr ?></span>

                    </div>



                    <!-- price -->
                    <div class="inp">
                        <label for="price">قیمت محصول</label>
                        <input value="<?php echo htmlspecialchars($md["PriceProduct"]) ?>" name="price" placeholder="قیمت هارا به تومان وارد کنید" id="price" type="text">
                        <span class="err"><?php echo $priceErr ?></span>
                    </div>

                    <button type="submit" name="send"><i class="ri-add-fill"></i>انتشار محصول</button>
                    <span class="err"><?php echo $importErr ?></span>
                    <span class="suc"><?php echo $create ?></span>


                </form>



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


<?php } else {
    header("location: ./admin-login.php");
    exit();
}





?>