<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$customer_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get customer details
$customer_query = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $customer_id");
if (mysqli_num_rows($customer_query) == 0) {
    header("Location: view_customers.php");
    exit();
}
$customer = mysqli_fetch_assoc($customer_query);

// Get customer orders
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<title>Customer Orders - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;"> Logout</a>
        </div>
        
        <h1>Orders for <?php echo htmlspecialchars($customer['fullname']); ?></h1>
        <div class="subtitle">Email: <?php echo htmlspecialchars($customer['email']); ?></div>
        
        <?php if(mysqli_num_rows($orders) == 0): ?>
            <p style="text-align: center; padding: 40px;">This customer has no orders yet.</p>
        <?php else: ?>
            <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td>KSH <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span style="background: <?php 
                                    echo $order['status'] == 'delivered' ? '#e8f5e9' : 
                                        ($order['status'] == 'shipped' ? '#e3f2fd' : 
                                        ($order['status'] == 'processing' ? '#fff3cd' : '#fce4ec')); 
                                ?>; padding: 5px 10px; border-radius: 20px;">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="manage_orders.php" 
                                   style="color: #1e3a5f; text-decoration: none;">📋 Manage</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <br>
        <a href="view_customers.php" class="back-link">← Back to Customers</a>
    </div>
</body>
</html>