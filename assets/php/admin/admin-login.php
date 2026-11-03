<?php


require __DIR__ . '/../../../config/db.php';
session_start();
if(isset($_SESSION["admin"])){
    header("location: ./admin-panel.php");
    exit();
}else{


define("ADMIN_PHONE" , "09940087764");
define("ADMIN_PASSWORD" , "3431qwer");
$password = "";
$phone = "";

$passwordErr = null;
$phoneErr = null;
$main_err = null;

function cleandata($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // fullname
    if (empty($_POST["phone"])) {

        $phoneErr = "* لطفا شماره خود را وارد کنید";
    } 

    if(strlen($_POST["phone"]) < 11){

    $phoneErr = "* شماره موبایل باید 11 رقم باشد";

    }
    
    else {

        $phone = cleandata($_POST["phone"]);
    }


    // phone
    if (empty($_POST["password"])) {

        $passwordErr = "* لطفا رمز عبور را وارد کنید";
    } else {

        $password = cleandata($_POST["password"]);


    }

    if ($phoneErr == null && $passwordErr == null) {

    if(ADMIN_PHONE === $phone && ADMIN_PASSWORD === $password){
        $_SESSION["admin"] = true;
        header("location: ./admin-panel.php");
    }else{
        $main_err = "شماره تلفن و یا رمز عبور اشتباه است";
    }
     
    }
}
   }

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
    <link rel="stylesheet" href="../../css/register/register.css">
    <link rel="stylesheet" href="../../css/reset.css">
    <!--======================= alert =======================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">

</head>

<body>

    <div class="main">
        <div class="content">
            <h3>ورود به پنل <span class="al">مدیریت</span></h3>
            <p>لطفا برای مدیریت سایت وارد پنل شوید..</p>
            <!--======================= form =======================-->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <div class="inp">
                    <label for="phone">شماره موبایل</label>
                    <div class="inps">
                        <input value="<?php echo $phone; ?>" name="phone" id="phone" placeholder="مثلا 09123456587" type="text">
                    </div>
                    <span class="err"><?php echo $phoneErr; ?></span>
                </div>

                <div class="inp">
                    <label for="password">رمز عبور</label>
                    <div class="inps">
                        <input id="password" placeholder="رمز عبور مدیر" name="password" type="password">
                    </div>
                    <span class="err"><?php echo $passwordErr; ?></span>
                </div>

                <button type="submit" name="submit">ورود</button>
                <span class="err"><?php echo $main_err; ?></span>        
            </form>
        </div>
    </div>



</body>

</html>