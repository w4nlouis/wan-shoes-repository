<?php
session_start();
if(!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard - Wan Shoes</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
            <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 20px; border-radius: 30px; text-decoration: none; font-size: 14px;">🚪 Logout</a>
        </div>
        
        <div class="logo">WAN SHOES</div>
        <h1>Employee Dashboard</h1>
        <div class="subtitle">Welcome, <?php echo ucfirst($name); ?> (<?php echo ucfirst($role); ?>)</div>
        
        <div class="menu-grid">
            <?php if($role == 'clerk'): ?>
                <a href="sales.php" class="menu-btn">💰 Record Sale</a>
            <?php elseif($role == 'stock_manager'): ?>
                <a href="add_stock.php" class="menu-btn">📦 Add Stock</a>
            <?php endif; ?>
            <a href="products.php" class="menu-btn">👟 View Products</a>
        </div>
    </div>
</body>
</html>