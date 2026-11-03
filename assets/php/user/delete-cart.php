<?php

require "../../../config/db.php";
session_start();

if(isset($_SESSION["status"]) && isset($_GET["id"])){
    
$productID = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM tblcart WHERE productID = ?");
$stmt->bind_param("i" , $productID);
if($stmt->execute()){
    $_SESSION["delete-cart"] = true;
    header("location: ./cart.php");
    exit();
}



}else{
    header("location: ./cart.php");
    exit();
}

?>