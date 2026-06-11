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
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
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