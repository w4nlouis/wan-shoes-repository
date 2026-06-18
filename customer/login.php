<?php
session_start();
include '../includes/config.php';

if (isset($_SESSION['customer_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_COOKIE['customer_email']) && isset($_COOKIE['customer_password'])) {
    $email = $_COOKIE['customer_email'];
    $password = $_COOKIE['customer_password'];

    $sql = "SELECT * FROM customers WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['fullname'];
            header("Location: dashboard.php");
            exit();
        }
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    } else {
        $sql = "SELECT * FROM customers WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $customer = mysqli_fetch_assoc($result);

        if ($customer && password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_name'] = $customer['fullname'];

            if ($remember) {
                setcookie('customer_email', $customer['email'], time() + (86400 * 30), "/");
                setcookie('customer_password', $password, time() + (86400 * 30), "/");
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <div class="container">
        <div class="logo-gold">WAN</div>
        <h2>Customer Login</h2>
        <p>Sign in to your account</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateLogin()">
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <div style="position: relative;">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span onclick="togglePassword('password', 'toggleLoginPassword')" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
            </div>
            <div style="text-align: left; margin: 10px 0;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top: 15px;">Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href="../index.html">← Back to Home</a></p>
    </div>

    <script>
        function validateLogin() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            if (email === '' || password === '') {
                alert('Email and password are required');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>