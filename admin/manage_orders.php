<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Update order status
if(isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE order_id = $order_id");
    $success = "Order #$order_id status updated to " . ucfirst($status);
}

// Get all orders with customer names
$orders = mysqli_query($conn, "SELECT orders.*, customers.fullname as customer_name 
                               FROM orders 
                               JOIN customers ON orders.customer_id = customers.customer_id 
                               ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
        <h1>Manage Orders</h1>
        <div class="subtitle">Update order statuses</div>
        
        <?php if(isset($success)): ?>
            <div style="background: #e8f5e9; padding: 15px; border-radius: 10px; color: #2e7d32; margin-bottom: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($orders) == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No orders yet</td>
                    </tr>
                <?php else: ?>
                    <?php while($order = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['customer_name']; ?></td>
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
                                <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <select name="status" style="padding: 5px; border-radius: 5px;">
                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                    <button type="submit" name="update_status" style="padding: 5px 15px; background: #1e3a5f; color: white; border: none; border-radius: 5px; cursor: pointer;">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>