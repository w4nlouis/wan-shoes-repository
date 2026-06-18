<?php
include 'includes/config.php';

$email = 'admin@wanshoes.com';
$password = 'admin123';

$result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
if ($row = mysqli_fetch_assoc($result)) {
    echo "Stored hash: " . $row['password'] . "<br>";
    echo "Password verify: " . (password_verify($password, $row['password']) ? "✅ MATCHES" : "❌ DOES NOT MATCH");
} else {
    echo "User not found";
}
?>