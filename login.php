<?php
session_start();
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // 1. CHECK ADMIN
    $result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['admin_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = 'admin';
            header("Location: admin/dashboard.php");
            exit();
        }
    }
    
    // 2. CHECK MANAGER (NO role check — just employees table)
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['employee_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = 'manager';
            header("Location: manager/dashboard.php");
            exit();
        }
    }
    
    // 3. CHECK CUSTOMER
    $result = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['fullname'];
            $_SESSION['role'] = 'customer';
            header("Location: customer/dashboard.php");
            exit();
        }
    }
    
    $error = "Invalid login";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wan Shoes Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="js/script.js"></script>
</head>
<body>
    <div class="container">
        <div class="logo-gold">WAN</div>
        <h2>Welcome Back</h2>
        <p>Sign in to your account</p>
        
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form method="POST" onsubmit="return validateLogin()">
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Email address" required>
            </div>
            
            <div class="form-group" style="position: relative;">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span id="toggleLoginPassword" onclick="togglePassword('password', 'toggleLoginPassword')" 
                      style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px;">
                      👁️
                </span>
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        
        <p style="margin-top: 20px; font-size: 11px; color: #999;">
            <strong>Demo Accounts:</strong><br>
            Admin: admin@wanshoes.com / admin123<br>
            Manager: manager@wanshoes.com / manager123<br>
            <a href="customer/register.php">Customer? Register here</a>
        </p>
    </div>
</body>
</html>