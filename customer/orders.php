<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Check for success/error messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success']);
unset($_SESSION['error']);

// Get customer orders
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>

        <h1>My Orders</h1>
        <div class="subtitle">View your purchase history</div>

        <?php if($success): ?>
            <div style="background: #e8f5e9; padding: 15px; border-radius: 10px; color: #2e7d32; margin-bottom: 20px;">
                ✅ <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div style="background: #fce4ec; padding: 15px; border-radius: 10px; color: #c62828; margin-bottom: 20px;">
                ❌ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if(mysqli_num_rows($orders) == 0): ?>
            <p style="text-align: center; padding: 40px;">No orders yet. <a href="products.php">Start shopping!</a></p>
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
                                        ($order['status'] == 'processing' ? '#fff3cd' : 
                                        ($order['status'] == 'cancelled' ? '#fce4ec' : '#fce4ec'))); 
                                ?>; padding: 5px 10px; border-radius: 20px;">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order_details.php?id=<?php echo $order['order_id']; ?>" 
                                   style="color: #1e3a5f; text-decoration: none;">📋 View</a>
                                <?php if($order['status'] == 'pending'): ?>
                                    | <a href="cancel_order.php?id=<?php echo $order['order_id']; ?>" 
                                         onclick="return confirm('Are you sure you want to cancel order #<?php echo $order['order_id']; ?>?')"
                                         style="color: #dc3545; text-decoration: none;">❌ Cancel</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <br>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>