<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get sales with LEFT JOIN
$sales = mysqli_query($conn, "SELECT sales.*, 
                              COALESCE(products.name, 'DELETED PRODUCT') as product_name, 
                              COALESCE(products.price, 0) as price, 
                              employees.name as employee_name 
                              FROM sales 
                              LEFT JOIN products ON sales.product_id = products.product_id 
                              JOIN employees ON sales.employee_id = employees.employee_id 
                              ORDER BY sale_date DESC");

// Calculate total revenue
$revenue_query = mysqli_query($conn, "SELECT SUM(sales.quantity * COALESCE(products.price, 0)) as total 
                                      FROM sales 
                                      LEFT JOIN products ON sales.product_id = products.product_id");
$revenue_row = mysqli_fetch_assoc($revenue_query);
$total_revenue = $revenue_row['total'] ?? 0;

// Count total sales
$count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM sales");
$count_row = mysqli_fetch_assoc($count_query);
$total_sales = $count_row['count'] ?? 0;

// Weekly sales for chart
$weekly = mysqli_query($conn, "SELECT DATE(sale_date) as date, SUM(quantity * COALESCE(products.price, 0)) as total 
                               FROM sales 
                               LEFT JOIN products ON sales.product_id = products.product_id 
                               WHERE sale_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                               GROUP BY DATE(sale_date)
                               ORDER BY date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sales - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .sales-table-container {
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            margin-top: 20px;
        }
        .sales-table-container table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .sales-table-container thead th {
            position: sticky;
            top: 0;
            background: #f8f8f8;
            z-index: 10;
            border-bottom: 2px solid #d4af37;
        }
        .summary-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-card {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 16px;
            flex: 1;
            text-align: center;
        }
        .summary-card .value {
            font-size: 32px;
            font-weight: bold;
        }
        .summary-card .value.gold {
            color: #d4af37;
        }
        .deleted-product {
            color: #dc3545;
            font-style: italic;
        }
        .chart-container {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
        }
        .chart-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .chart-item {
            flex: 1;
            min-width: 50px;
            text-align: center;
            background: #fff;
            padding: 15px 10px;
            border-radius: 8px;
            border-bottom: 3px solid #d4af37;
        }
        .chart-item .date {
            font-size: 12px;
            color: #666;
        }
        .chart-item .amount {
            font-weight: bold;
            color: #1e3a5f;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
        <h1>Sales Reports</h1>
        <div class="subtitle">View all sales transactions and revenue</div>
        
        <!-- Sales Chart -->
        <div class="chart-container">
            <h3>📈 Sales Overview (Last 7 Days)</h3>
            <?php if (mysqli_num_rows($weekly) > 0): ?>
                <div class="chart-bar">
                    <?php while($day = mysqli_fetch_assoc($weekly)): ?>
                        <div class="chart-item">
                            <div class="date"><?php echo date('M d', strtotime($day['date'])); ?></div>
                            <div class="amount">KSH <?php echo number_format($day['total'], 0); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="color: #666;">No sales data available for the last 7 days.</p>
            <?php endif; ?>
        </div>
        
        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <h3>📊 TOTAL SALES</h3>
                <div class="value"><?php echo $total_sales; ?></div>
            </div>
            <div class="summary-card">
                <h3>💰 TOTAL REVENUE</h3>
                <div class="value gold">KSH <?php echo number_format($total_revenue, 2); ?></div>
            </div>
        </div>
        
        <!-- Sales Table -->
        <h3>Sales Transactions</h3>
        <div class="sales-table-container">
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Price (KSH)</th>
                        <th>Clerk</th>
                        <th>Quantity</th>
                        <th>Total (KSH)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $displayed = 0;
                    while($row = mysqli_fetch_assoc($sales)): 
                        $displayed++;
                        $total = $row['quantity'] * $row['price'];
                        $is_deleted = ($row['product_name'] == 'DELETED PRODUCT');
                    ?>
                        <tr>
                            <td><?php echo $row['sale_id']; ?></td>
                            <td><?php if($is_deleted): ?>
                                <span class="deleted-product"><?php echo $row['product_name']; ?> (ID: <?php echo $row['product_id']; ?>)</span>
                            <?php else: ?>
                                <?php echo $row['product_name']; ?>
                            <?php endif; ?></td>
                            <td><?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['employee_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><strong><?php echo number_format($total, 2); ?></strong></td>
                            <td><?php echo $row['sale_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    
                    <?php if($displayed == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No sales recorded yet</td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; background: #f8f8f8;">
                                <strong>Showing <?php echo $displayed; ?> of <?php echo $total_sales; ?> total sales</strong>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>