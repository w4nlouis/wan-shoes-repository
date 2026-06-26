<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get all orders with customer names and item details
$sales = mysqli_query($conn, "SELECT orders.order_id, 
                                     orders.order_date, 
                                     orders.total_amount, 
                                     orders.status,
                                     customers.fullname as customer_name,
                                     GROUP_CONCAT(CONCAT(products.name, ' x', order_items.quantity) SEPARATOR ', ') as items
                              FROM orders 
                              JOIN customers ON orders.customer_id = customers.customer_id
                              JOIN order_items ON orders.order_id = order_items.order_id
                              JOIN products ON order_items.product_id = products.product_id
                              GROUP BY orders.order_id
                              ORDER BY orders.order_date DESC");

// Calculate total revenue
$revenue_query = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
$revenue_row = mysqli_fetch_assoc($revenue_query);
$total_revenue = $revenue_row['total'] ?? 0;

// Count total orders
$count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
$count_row = mysqli_fetch_assoc($count_query);
$total_orders = $count_row['count'] ?? 0;

// Weekly sales chart
$weekly = mysqli_query($conn, "SELECT DATE(order_date) as date, SUM(total_amount) as total 
                               FROM orders 
                               WHERE order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                               GROUP BY DATE(order_date)
                               ORDER BY date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sales - Wan Shoes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .status-pending { background: #fce4ec; }
        .status-processing { background: #fff3cd; }
        .status-shipped { background: #e3f2fd; }
        .status-delivered { background: #e8f5e9; }
        .status-cancelled { background: #f5f5f5; color: #999; }
    </style>
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;"> Logout</a>
        </div>
        
        <h1>Sales Reports</h1>
        <div class="subtitle">View all orders and revenue</div>
        
        <!-- Sales Chart -->
        <div class="chart-container">
            <h3> Sales Overview (Last 7 Days)</h3>
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
                <h3> TOTAL ORDERS</h3>
                <div class="value"><?php echo $total_orders; ?></div>
            </div>
            <div class="summary-card">
                <h3> TOTAL REVENUE</h3>
                <div class="value gold">KSH <?php echo number_format($total_revenue, 2); ?></div>
            </div>
        </div>
        
        <!-- Orders Table -->
        <h3>Order Transactions</h3>
        <div class="sales-table-container">
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total (KSH)</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $displayed = 0;
                    while($row = mysqli_fetch_assoc($sales)): 
                        $displayed++;
                        $status_class = 'status-' . $row['status'];
                    ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td><?php echo $row['items']; ?></td>
                            <td><strong>KSH <?php echo number_format($row['total_amount'], 2); ?></strong></td>
                            <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                            <td><?php echo $row['order_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    
                    <?php if($displayed == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No orders recorded yet</td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; background: #f8f8f8;">
                                <strong>Showing <?php echo $displayed; ?> of <?php echo $total_orders; ?> total orders</strong>
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