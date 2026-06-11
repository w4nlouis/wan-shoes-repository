<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit();
    }
    
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $row['role'];
        header("Location: dashboard.php");
        exit();
    }
    
    $error = "Invalid login";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wan Shoes Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <div class="container">
        <h2>Wan Shoes Inventory</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST" onsubmit="return validateLogin()">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top: 15px; font-size: 12px;">admin@wanshoes.com / admin123</p>
    </div>
</body>
</html>