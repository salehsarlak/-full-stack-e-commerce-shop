<?php


require __DIR__ . '/../../../config/db.php';
session_start();


// notification
if(isset($_SESSION["not-user"])){
    echo "<script>
    window.addEventListener('DOMContentLoaded', function () {
        iziToast.show({
            message: 'ابندا حساب کاربری بسازید',
            position: 'topRight',
            timeout: 3000,
            rtl: true,
            color: 'red',
            icon: 'ri-error-warning-line'   // ← علامت تعجب داخل دایره/مثلث
        });
    });
</script>";
unset($_SESSION["not-user"]);
}
    // check login

    if(isset($_SESSION["status"]) && $_SESSION["status"] === true && isset($_SESSION["userid"]) ){
        header("location: ./userpanel.php");
        exit();
    }

    if(isset($_SESSION["admin"])){
        header("location: ../admin/admin-panel.php");
        exit();
    }

$main_err = null;
$fullname = "";
$phone = "";

$fullnameErr = null;
$phoneErr = null;

function cleandata($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // fullname
    if (empty($_POST["fullname"])) {

        $fullnameErr = "* لطفا نام خود را وارد کنید";
    } else {

        $fullname = cleandata($_POST["fullname"]);
    }


    // phone
    if (empty($_POST["phone"])) {

        $phoneErr = "* لطفا شماره موبایل را وارد کنید";
    } else {

        $phone = cleandata($_POST["phone"]);

        if (strlen($phone) != 11) {
            $phoneErr = "* شماره موبایل باید 11 رقم باشد";
        }
    }

    if ($phoneErr == null && $fullnameErr == null) {
        

        // select

        $stmt = $conn->prepare("SELECT username , phonenumber , userid FROM tblusers WHERE (username = ? AND phonenumber = ?)");
        $stmt->bind_param("ss", $fullname, $phone);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();
        ?><script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
            <script src="../assets/js/alert.js"></script><?php
                                                            $_SESSION["userid"] = $user["userid"];
                                                            $_SESSION["status"] = true;
                                                            header("location: ./userpanel.php");
                                                        } elseif ($result->num_rows < 1) {
                                                            $stmt = $conn->prepare("INSERT INTO tblusers (username , phonenumber) VALUES(? , ?)");
                                                            $stmt->bind_param("ss", $fullname, $phone);
                                                            $stmt->execute();
                                                            $user = $conn->insert_id;
                                                            $_SESSION["userid"] = $user;
                                                            $_SESSION["status"] = true;
                                                            header("Location: ./userpanel.php");
                                                                                exit();
                                                        }
                                                        // $result = $result->fetch_assoc();



                                                    }else{
                                                      $main_err = "شماره تلفن و یا رمز عبور اشتباه است";
                                                             }
                                                    
                                                }



                                                            ?>
<!DOCTYPE html>
<html lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود و ثبت نام بازارون</title>
    <!--======================= REMIXICONS =======================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.min.css">
    <!--======================= css =======================-->
    <link rel="stylesheet" href="../../css/register/register.css">
    <link rel="stylesheet" href="../../css/reset.css">
    <!--======================= alert =======================-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
        <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

</head>

<body>

    <div class="main">
        <div class="content">
           <a href="../../../index.php"><img src="../../images/logo2.webp" alt=""></a> 
            <h3>ورود و ثبت نام در <span class="al">بازارون</span></h3>
            <p>برای استفاده از امکانات حساب بسازید یا وارد شوید.</p>
            <!--======================= form =======================-->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <div class="inp">
                    <label for="fullname">نام و نام خانوادگی</label>
                    <div class="inps">
                        <input name="fullname" id="fullname" placeholder="مثلا علی احمدی" type="text">
                    </div>
                    <span class="err"><?php echo $fullnameErr; ?></span>
                </div>

                <div class="inp">
                    <label for="phone">شماره موبایل</label>
                    <div class="inps">
                        <input id="phone" placeholder="09123457865" name="phone" type="text">
                    </div>
                    <span class="err"><?php echo $phoneErr; ?></span>
                </div>

                <button type="submit" name="submit">ثبت نام</button>

            </form>
        </div>
    </div>



</body>

</html>