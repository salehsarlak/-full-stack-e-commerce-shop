<?php
require "../../../config/db.php";
session_start();

if(isset($_SESSION["status"]) && isset($_GET["id"])){
    $stmt = $conn->prepare("UPDATE tblcart SET paymentStatus = 'paid' WHERE userID = ?");
    $stmt->bind_param("i" , $_GET["id"]);
    if($stmt->execute()){
        $_SESSION["send"] = true;
        header("location: ./cart.php");
        exit();
    }
}


?>