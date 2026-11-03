<?php

require "../../../config/db.php";
session_start();

if (isset($_SESSION["admin"])) {

    $stmt = $conn->prepare("SELECT * FROM `tblusers` WHERE userid = ?");
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // function

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $usernameErr = "";
    $phoneErr = "";
    $passwordErr = "";



    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = test_input($_POST["username"]);

        if (empty($username)) {
            $usernameErr = "* نام کاربری الزامی است";
        } elseif (is_numeric($username[0])) {
            $usernameErr = "* نام کاربری نمی‌تواند با عدد شروع شود";
        }

        // phonenumber
        $phone = test_input($_POST["phone"]);

        if (!is_numeric($phone) || strlen($phone) != 11) {
            $phoneErr = "* شماره موبایل باید یک عدد 11 رقمی باشد";
        }


        // pass

        // import
        if ($usernameErr == "" && $phoneErr == "" && $passwordErr == "") {

            $stmt = $conn->prepare("UPDATE tblusers SET username = ? , phonenumber = ?  WHERE userid = ?");
            $stmt->bind_param("ssi", $username, $phone, $_GET["id"]);
            if ($stmt->execute()) {
                $_SESSION["user-edit"] = true;
                header("location: ./users.php");
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
            <h3><i class="ri-store-2-line"></i>فرم ویرایش کاربر</h3>
            <p>صفحه ویرایش کاربر بازارون</p>


            <!-- form -->

            <div class="form-wrapper">
                <form method="POST" action="">
                    <!-- title -->
                    <div class="inp">
                        <label for="p-title">نام کاربر</label>
                        <input value="<?Php echo htmlspecialchars($user["username"]) ?>" name="username" id="p-title" type="text">
                        <span class="err"><?php echo $usernameErr ?></span>
                    </div>
                    <!-- upload image -->
                    <div class="inp">

                        <label for="">شماره موبایل </label>
                        <input name="phone" value="<?php echo htmlspecialchars($user["phonenumber"]); ?>" id="p-title" type="text">
                        <span class="err"><?php echo $phoneErr ?></span>

                    </div>


                    <button type="submit" name="send"><i class="ri-add-fill"></i>ویرایش کاربر</button>
                    <!-- <span class="suc"><?php echo $create ?></span> -->


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

<?php
}else{
    header("location: ./admin-login.php");
    exit();
}



?>