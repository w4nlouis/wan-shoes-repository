 <?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success = "";
$error = "";

// Get customer data
$result = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $customer_id");
$customer = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email already exists for another customer
    $check = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email' AND customer_id != $customer_id");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already in use by another account";
    } else {
        $sql = "UPDATE customers SET fullname='$fullname', email='$email' WHERE customer_id=$customer_id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['customer_name'] = $fullname;
            $success = "Profile updated successfully!";
            // Refresh customer data
            $result = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $customer_id");
            $customer = mysqli_fetch_assoc($result);
        } else {
            $error = "Update failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>

        <h1>My Profile</h1>
        <div class="subtitle">Update your account details</div>

        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div style="color: green; background: #e8f5e9; padding: 10px; border-radius: 10px;"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($customer['fullname']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            </div>
            <button type="submit">Update Profile</button>
        </form>

        <br>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>