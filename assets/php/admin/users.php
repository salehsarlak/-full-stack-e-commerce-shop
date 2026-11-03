    <?php

    require "../../../config/db.php";
    session_start();

    if (isset($_SESSION["admin"])) {

        $stmt = $conn->prepare("SELECT * FROM tblusers");

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);


        //  

        if (isset($_SESSION["user-edit"])) {
            echo "<script>
        window.addEventListener('DOMContentLoaded', function () {
            iziToast.success({
                message: 'اطلاعات کاربر ویرایش شد',
                position: 'topRight',
                timeout: 3000,
                rtl: true
            });
        });
    </script>";
            unset($_SESSION["user-edit"]);
        }

        // delete notification
        if (isset($_SESSION["delete-user"])) {
            echo "<script>
    window.addEventListener('DOMContentLoaded', function () {
        iziToast.show({
            message: 'کاربر مورد نظر حذف شد',
            position: 'topRight',
            timeout: 3000,
            rtl: true,
            color: 'red',
            icon: 'ri-delete-bin-7-line'
        });
    });
</script>";
            unset($_SESSION["delete-user"]);
        }


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
                <h3><i class="ri-menu-5-line"></i>تعدا د کاربران سایت : <?php echo htmlspecialchars(mysqli_num_rows($result)); ?></h3>
                <p>لیست کاربرانی که در سایت وجود دارند</p>

                <!-- list -->
                <div class="list">

                    <table>

                        <!-- label -->
                        <thead>
                            <tr>
                                <th>آِیدی</th>
                                <th>نام کاربر</th>
                                <th>شماره موبایل</th>
                                <th>رمز عبور</th>
                                <th>ویرایش کاربر</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user as $key => $item) {


                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item["userid"]); ?></td>
                                    <td>
                                        <h4><?php echo htmlspecialchars($item["username"]); ?></h4>
                                    </td>
                                    <td><?php echo htmlspecialchars($item["phonenumber"]); ?></td>
                                    <td><span>****</span></td>
                                    <td><a class="ed" href="./edit-user.php?id=<?php echo htmlspecialchars($item["userid"]); ?>" data-id="<?php echo ($item["userid"]) ?>" id="edit"><i class="ri-pencil-line"></i>ویرایش</a></td>
                                    <td><a href="delete-user.php?id=<?php echo htmlspecialchars($item["userid"]); ?>" id="openbtn"><i class="ri-delete-bin-7-line"></i>حذف</a></td>
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
                    <span>آیا کاربر حذف شود؟</span>
                    <p>پس از کلیک روی حذف کاربر دیگر قابل بازیابی نیست </p>
                    <a href="delete-user.php?id=<?php echo htmlspecialchars($item["userid"]); ?>">حذف</a>
                    <button id="closebtn">بیخیال</button>

                </div>
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


            // openpopup
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

    <?php
    } else {
        header("location: ./admin-login.php");
        exit();
    }


    ?>