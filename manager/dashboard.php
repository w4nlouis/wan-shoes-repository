<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: ../login.php");
    exit();
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Manager';

// Stock alert: Products with low stock (less than 5)
$low_stock = mysqli_query($conn, "SELECT * FROM products WHERE stock_quantity < 5");
$low_stock_count = mysqli_num_rows($low_stock);

// Get total products count
$total_products = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$total_products_row = mysqli_fetch_assoc($total_products);
$total_products_count = $total_products_row['count'] ?? 0;

// Get total stock value
$stock_value = mysqli_query($conn, "SELECT SUM(stock_quantity * price) as total FROM products");
$stock_value_row = mysqli_fetch_assoc($stock_value);
$total_stock_value = $stock_value_row['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <!-- Top Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
        <!-- Header -->
        <h1>Manager Dashboard</h1>
        <div class="subtitle">Welcome, <?php echo ucfirst($name); ?>!</div>
        
        <!-- Stock Alert -->
        <?php if($low_stock_count > 0): ?>
            <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px;">
                <p style="color: #856404; font-weight: bold; margin-bottom: 5px;">⚠️ Low Stock Alert!</p>
                <p style="color: #856404;">The following products have less than 5 units left:</p>
                <ul style="margin-left: 20px; margin-top: 5px; color: #856404;">
                    <?php while($item = mysqli_fetch_assoc($low_stock)): ?>
                        <li><?php echo $item['name']; ?> - Brand: <?php echo $item['brand']; ?> - Stock: <?php echo $item['stock_quantity']; ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>📦 PRODUCTS</h3>
                <div class="value"><?php echo $total_products_count; ?></div>
                <div class="label">Total Items</div>
            </div>
            <div class="stat-card">
                <h3>💰 INVENTORY VALUE</h3>
                <div class="value">KSH <?php echo number_format($total_stock_value, 2); ?></div>
                <div class="label">Stock Worth</div>
            </div>
            <div class="stat-card">
                <h3>⚠️ LOW STOCK</h3>
                <div class="value" style="color: <?php echo ($low_stock_count > 0) ? '#dc3545' : '#28a745'; ?>;"><?php echo $low_stock_count; ?></div>
                <div class="label"><?php echo ($low_stock_count > 0) ? 'Need Attention' : 'All Stock Healthy'; ?></div>
            </div>
        </div>
        
        <!-- Menu Grid -->
        <div class="menu-grid">
            <a href="add_stock.php" class="menu-btn">📦 Add Stock</a>
            <a href="../admin/products.php" class="menu-btn">👟 View Products</a>
        </div>
    </div>
</body>
</html>