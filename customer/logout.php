<?php
session_start();
session_destroy();

if (isset($_COOKIE['customer_email'])) {
    setcookie('customer_email', '', time() - 3600, "/");
}
if (isset($_COOKIE['customer_password'])) {
    setcookie('customer_password', '', time() - 3600, "/");
}

header("Location: login.php");
exit();
?>