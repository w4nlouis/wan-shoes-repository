<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Set default name if not set
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';

$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

$stock_query = mysqli_query($conn, "SELECT SUM(stock_quantity * price) as total FROM products");
$stock_row = mysqli_fetch_assoc($stock_query);
$total_stock_value = $stock_row['total'] ?? 0;

$product_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$product_count_row = mysqli_fetch_assoc($product_count_query);
$total_products = $product_count_row['count'] ?? 0;

$employee_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM employees");
$employee_count_row = mysqli_fetch_assoc($employee_count_query);
$total_employees = $employee_count_row['count'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Wan Shoes</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
        <h1>Admin Dashboard</h1>
        <div class="subtitle">Welcome, <?php echo ucfirst($name); ?>!</div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>📦 PRODUCTS</h3>
                <div class="value"><?php echo $total_products; ?></div>
                <div class="label">Total Items</div>
            </div>
            <div class="stat-card">
                <h3>💰 INVENTORY VALUE</h3>
                <div class="value">KSH <?php echo number_format($total_stock_value, 2); ?></div>
                <div class="label">Stock Worth</div>
            </div>
            <div class="stat-card">
                <h3>👥 STAFF</h3>
                <div class="value"><?php echo $total_employees; ?></div>
                <div class="label">Active Employees</div>
            </div>
        </div>
        
        <div class="menu-grid">
            <a href="products.php" class="menu-btn">📦 Manage Products</a>
            <a href="employees.php" class="menu-btn">👥 Manage Employees</a>
            <a href="view_sales.php" class="menu-btn">💰 View Sales</a>
        </div>
    </div>
</body>
</html>