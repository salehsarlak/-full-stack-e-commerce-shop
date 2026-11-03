<?php
session_start();
require "../../../config/db.php";
if (isset($_SESSION["admin"]) && filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
    $stmt = $conn->prepare("DELETE FROM tblproducts WHERE productID = ?");
    $stmt->bind_param("i", $_GET["id"]);
    if ($stmt->execute()) {
        $_SESSION["delete"] = true;
        header("location: ./products.php");
        exit();
    } else {
        echo "مکشلی در حذف محصول وجود دارد";
    }
} else {
    header("location: ../admin-login.php");
    exit();
}
