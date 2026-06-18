<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get all customers
$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Customers - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
        <h1>Registered Customers</h1>
        <div class="subtitle">View all customer accounts</div>
        
        <?php if(mysqli_num_rows($customers) == 0): ?>
            <p style="text-align: center; padding: 40px;">No customers registered yet.</p>
        <?php else: ?>
            <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Orders</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($customer = mysqli_fetch_assoc($customers)): 
                        // Count orders for this customer
                        $order_count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE customer_id = " . $customer['customer_id']);
                        $order_count = mysqli_fetch_assoc($order_count_query);
                    ?>
                        <tr>
                            <td><?php echo $customer['customer_id']; ?></td>
                            <td><?php echo $customer['fullname']; ?></td>
                            <td><?php echo $customer['email']; ?></td>
                            <td><?php echo date('d M Y', strtotime($customer['created_at'])); ?></td>
                            <td><?php echo $order_count['count']; ?></td>
                            <td>
                                <a href="view_customer_orders.php?id=<?php echo $customer['customer_id']; ?>" 
                                   style="color: #1e3a5f; text-decoration: none;">📋 View Orders</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>