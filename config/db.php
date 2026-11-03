
<?php

$servername  = "localhost";
$username  = "root";
$password  = "";
$dbname  = "dbpanel";



$conn = new mysqli($servername , $username , $password , $dbname);

if($conn->connect_error){
    die("disconnected" . $conn->connect_error);
}


?>