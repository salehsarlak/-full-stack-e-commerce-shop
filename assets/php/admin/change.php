<?php

require "../../../config/db.php";
session_start();

if(isset($_SESSION["admin"]) && isset($_GET["id"]) && is_numeric($_GET["id"])){
    
   $stmt = $conn->prepare("SELECT * FROM tblcart WHERE CartID = ?");
   $stmt->bind_param("i" , $_GET["id"]);
   $stmt->execute();
   $result = $stmt->get_result();
   $cart = $result->fetch_assoc();

   if($cart["paymentStatus"] === 'paid'){
        $status = "complete";
    }elseif($cart["paymentStatus"] === 'complete'){
    $status = "paid";
   }
   
   $stmt = $conn->prepare("UPDATE tblcart SET paymentStatus = ? WHERE CartID = ?");
   $stmt->bind_param("si" , $status ,  $_GET["id"]);
    if($stmt->execute()){
        $_SESSION["order"] = true;
        header("location: ./orders.php");
        exit();


}else{
        header("location: ./orders.php");
        exit();
}
}


?>