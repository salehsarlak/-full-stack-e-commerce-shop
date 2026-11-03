<?php

require "../../../config/db.php";
session_start();

if(isset($_SESSION["status"])){

$productID = filter_input(INPUT_POST , "productID" , FILTER_VALIDATE_INT);

if(!$productID || $productID < 1){
    header("location: ../../../index.php");
    exit();
}

$userid = $_SESSION["userid"];

$stmt = $conn->prepare("SELECT * FROM tblproducts WHERE productID = ?");
$stmt->bind_param("i" , $productID);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
        header("location: ../../../index.php");
         exit();
}

// search the cart

$stmt = $conn->prepare("SELECT CartID FROM tblcart WHERE userID = ? AND productID = ? AND paymentStatus = 'pending'");
$stmt->bind_param("ii" , $userid , $productID);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // boost quantity 
    $stmt = $conn->prepare("UPDATE tblcart SET quantity = quantity + 1 WHERE userID = ? AND productID = ? AND paymentStatus = 'pending' ");
    $stmt->bind_param("ii" , $userid , $productID);
    $stmt->execute();
}else{
    // add
    $stmt = $conn->prepare("INSERT INTO tblcart (userID , productID , quantity , paymentStatus) VALUES (? , ? , 1 , 'pending')");
    $stmt->bind_param("ii" , $userid , $productID);
    if($stmt->execute()){
        $_SESSION["add-tocart"] = true;
    }
}


header("location: ./cart.php");
exit();


}else{
    $_SESSION["not-user"] = true;
    header("location: ./reg.php");
    exit();
}




?>