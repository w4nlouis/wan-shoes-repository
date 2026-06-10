<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

$sales = mysqli_query($conn, "SELECT sales.*, products.name as product_name, products.price, employees.name as employee_name 
                              FROM sales 
                              JOIN products ON sales.product_id = products.product_id 
                              JOIN employees ON sales.employee_id = employees.employee_id 
                              ORDER BY sale_date DESC");

$revenue_query = mysqli_query($conn, "SELECT SUM(sales.quantity * products.price) as total 
                                      FROM sales 
                                      JOIN products ON sales.product_id = products.product_id");
$revenue_row = mysqli_fetch_assoc($revenue_query);
$total_revenue = $revenue_row['total'] ?? 0;

$count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM sales");
$count_row = mysqli_fetch_assoc($count_query);
$total_sales = $count_row['count'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sales - Wan Shoes</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none; font-size: 14px; font-weight: 500;">🚪 Logout</a>
        </div>
        
        <h1>Sales Reports</h1>
        
        <div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
            <div style="background: #f8f8f8; padding: 20px; border-radius: 16px; flex: 1; text-align: center;">
                <h3>Total Sales</h3>
                <p style="font-size: 32px; font-weight: bold; color: #000;"><?php echo $total_sales; ?></p>
            </div>
            <div style="background: #f8f8f8; padding: 20px; border-radius: 16px; flex: 1; text-align: center;">
                <h3>Total Revenue</h3>
                <p style="font-size: 32px; font-weight: bold; color: #d4af37;">KSH <?php echo number_format($total_revenue, 2); ?></p>
            </div>
        </div>
        
        <h3>All Sales Records</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th><th>Product</th><th>Price</th><th>Clerk</th><th>Quantity</th><th>Total</th><th>Date</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($sales)): 
                $total = $row['quantity'] * $row['price'];
            ?>
            <tr>
                <td><?php echo $row['sale_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td>KSH <?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['employee_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>KSH <?php echo number_format($total, 2); ?></td>
                <td><?php echo $row['sale_date']; ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if(mysqli_num_rows($sales) == 0): ?>
                <tr><td colspan="7" style="text-align: center;">No sales recorded yet</td></tr>
            <?php endif; ?>
        </table>
        
        <br>
        <a href="admin.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>