<?php
require "../../../config/db.php";
session_start();
if (isset($_SESSION["admin"]) && filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
    $stmt = $conn->prepare("DELETE FROM tblusers WHERE userid = ?");
    $stmt->bind_param("i", $_GET["id"]);
    if ($stmt->execute()) {
        $_SESSION["delete-user"] = true;
        header("location: ./users.php");
        exit();
    }
}
