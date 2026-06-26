<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';

$stock_query = mysqli_query($conn, "SELECT SUM(stock_quantity * price) as total FROM products");
$stock_row = mysqli_fetch_assoc($stock_query);
$total_stock_value = $stock_row['total'] ?? 0;

$product_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$product_count_row = mysqli_fetch_assoc($product_count_query);
$total_products = $product_count_row['count'] ?? 0;

$employee_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM employees");
$employee_count_row = mysqli_fetch_assoc($employee_count_query);
$total_employees = $employee_count_row['count'] ?? 0;

$order_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
$order_count_row = mysqli_fetch_assoc($order_count_query);
$total_orders = $order_count_row['count'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<title>Admin Dashboard - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
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
            <div class="stat-card">
                <h3>📋 ORDERS</h3>
                <div class="value"><?php echo $total_orders; ?></div>
                <div class="label">Total Orders</div>
            </div>
        </div>
        
        <div class="menu-grid">
            <a href="products.php" class="menu-btn">📦 Manage Products</a>
            <a href="employees.php" class="menu-btn">👥 Manage Employees</a>
            <a href="view_sales.php" class="menu-btn">💰 View Sales</a>
            <a href="manage_orders.php" class="menu-btn">📋 Manage Orders</a>
            <a href="view_customers.php" class="menu-btn">👤 View Customers</a>
        </div>
    </div>
</body>
</html>