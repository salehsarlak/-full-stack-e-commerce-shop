<?php

session_start();

if(isset($_SESSION["admin"])){
    unset($_SESSION["admin"]);
    header("location: ../../../index.php");
    exit();
}else{
    header("location: ../../../index.php");
    exit();
}

 
?>
