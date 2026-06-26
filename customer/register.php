<?php
session_start();
include '../includes/config.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($fullname) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered. Please login.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customers (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <div class="container">
        <div class="logo-gold">WAN</div>
        <h2>Create Customer Account</h2>
        <p>Join Wan Shoes family</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success" style="color: green; background: #e8f5e9; padding: 10px; border-radius: 10px;"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateRegistration()">
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <div style="position: relative;">
                <input type="password" id="password" name="password" placeholder="Password (min 8 chars)" required>
                <span onclick="togglePassword('password', 'toggleRegPassword')" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
            </div>
            <div style="position: relative;">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <span onclick="togglePassword('confirm_password', 'toggleRegConfirm')" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
            </div>
            <div id="register-password-strength" style="text-align: left; font-size: 12px; margin-bottom: 10px;"></div>
            <button type="submit">Register</button>
        </form>
        <p style="margin-top: 15px;">Already have an account? <a href="login.php">Login here</a></p>
        <p><a href="../index.html">← Back to Home</a></p>
    </div>

    <script>
        document.getElementById('password').addEventListener('keyup', function() {
            const strengthDiv = document.getElementById('register-password-strength');
            const val = this.value;
            let strength = '', color = '';
            if (val.length === 0) { strengthDiv.innerHTML = ''; return; }
            if (val.length < 4) { strength = 'Weak'; color = 'red'; }
            else if (val.length < 8) { strength = 'Medium'; color = 'orange'; }
            else { strength = 'Strong'; color = 'green'; }
            strengthDiv.innerHTML = 'Password strength: <span style="color:' + color + '; font-weight: bold;">' + strength + '</span>';
        });

        function validateRegistration() {
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (fullname === '' || email === '' || password === '') {
                alert('All fields are required');
                return false;
            }
            if (password !== confirm) {
                alert('Passwords do not match');
                return false;
            }
            if (password.length < 8) {
                alert('Password must be at least 8 characters');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>