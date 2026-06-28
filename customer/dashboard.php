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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="logo">WAN SHOES</div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 14px; color: #666;">Hi, <?php echo htmlspecialchars($name); ?></span>
                <button class="mobile-menu-btn" onclick="toggleMobileNav()">☰</button>
                <a href="logout.php" class="desktop-nav" style="background: #dc3545; color: white; padding: 8px 20px; border-radius: 30px; text-decoration: none;"> Logout</a>
            </div>
        </div>

        <div class="mobile-nav" id="mobileNav">
            <a href="dashboard.php" style="color: #d4af37; font-weight: 600;"> Dashboard</a>
            <a href="products.php"> Products</a>
            <a href="cart.php"> Cart</a>
            <a href="orders.php"> Orders</a>
            <a href="profile.php"> Profile</a>
        </div>

        <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
        <div class="subtitle">Customer Dashboard</div>

        <div class="menu-grid">
            <a href="products.php" class="menu-btn"> Browse Products</a>
            <a href="cart.php" class="menu-btn"> My Cart</a>
            <a href="orders.php" class="menu-btn"> My Orders</a>
            <a href="profile.php" class="menu-btn"> My Profile</a>
        </div>
    </div>

    <script>
        function toggleMobileNav() {
            var nav = document.getElementById('mobileNav');
            if (nav) {
                nav.classList.toggle('open');
            }
        }
    </script>
</body>
</html>