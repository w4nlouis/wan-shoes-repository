<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['admin_id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit();
    }
    
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['employee_id'];
        $_SESSION['name'] = $row['name'];
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
    <title>Wan Shoes - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="js/script.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .logo-gold {
            color: #d4af37;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-gold">WAN</div>
        <h2>Welcome Back</h2>
        <p>Sign in to access inventory</p>
        
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form method="POST" onsubmit="return validateLogin()">
            <input type="email" id="email" name="email" placeholder="Email address" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        
        <p style="margin-top: 20px; font-size: 11px; color: #999;">Demo: admin@wanshoes.com / admin123</p>
    </div>
</body>
</html>