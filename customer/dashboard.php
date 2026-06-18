<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['customer_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <!-- TOP BAR -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>

        <!-- HEADER -->
        <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
        <div class="subtitle">Customer Dashboard</div>

        <!-- MENU GRID -->
        <div class="menu-grid">
            <a href="products.php" class="menu-btn">👟 Browse Products</a>
            <a href="cart.php" class="menu-btn">🛒 My Cart</a>
            <a href="orders.php" class="menu-btn">📦 My Orders </a>
            <a href="profile.php" class="menu-btn">👤 My Profile </a>
        </div>
    </div>
</body>
</html>