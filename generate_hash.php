<?php
include 'includes/config.php';

// New hash for admin123
$new_hash = password_hash('admin123', PASSWORD_DEFAULT);

// Update admin password
mysqli_query($conn, "UPDATE admins SET password = '$new_hash' WHERE email = 'admin@wanshoes.com'");

echo " Password updated for admin@wanshoes.com<br>";
echo "New hash: " . $new_hash . "<br>";

// Also update employees
$employee_hash = password_hash('clerk123', PASSWORD_DEFAULT);
mysqli_query($conn, "UPDATE employees SET password = '$employee_hash' WHERE email = 'clerk@wanshoes.com'");

$manager_hash = password_hash('manager123', PASSWORD_DEFAULT);
mysqli_query($conn, "UPDATE employees SET password = '$manager_hash' WHERE email = 'manager@wanshoes.com'");

echo " Clerk and Manager passwords also updated.";
?>