<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "wan_shoes_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('Africa/Nairobi');
?>