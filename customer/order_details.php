<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['id'] ?? 0;
$customer_id = $_SESSION['customer_id'];

// Get order details
$order = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = $order_id AND customer_id = $customer_id");
if (mysqli_num_rows($order) == 0) {
    header("Location: orders.php");
    exit();
}
$order_data = mysqli_fetch_assoc($order);

// Get order items
$items = mysqli_query($conn, "SELECT order_items.*, products.name, products.brand 
                              FROM order_items 
                              JOIN products ON order_items.product_id = products.product_id 
                              WHERE order_items.order_id = $order_id");
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;"> Logout</a>
        </div>

        <h1>Order #<?php echo $order_id; ?></h1>
        <div class="subtitle">Placed on <?php echo $order_data['order_date']; ?></div>

        <div style="background: #f8f8f8; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <p><strong>Status:</strong> 
                <span style="background: <?php 
                    echo $order_data['status'] == 'delivered' ? '#e8f5e9' : 
                        ($order_data['status'] == 'shipped' ? '#e3f2fd' : 
                        ($order_data['status'] == 'processing' ? '#fff3cd' : 
                        ($order_data['status'] == 'cancelled' ? '#fce4ec' : '#fce4ec'))); 
                ?>; padding: 5px 10px; border-radius: 20px;">
                    <?php echo ucfirst($order_data['status']); ?>
                </span>
            </p>
            <p><strong>Total:</strong> KSH <?php echo number_format($order_data['total_amount'], 2); ?></p>
        </div>

        <h3>Items</h3>
        <table border="1" cellpadding="10" style="width: 100%;">
            <tr>
                <th>Product</th>
                <th>Brand</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php while($item = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['brand']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>KSH <?php echo number_format($item['price'], 2); ?></td>
                    <td>KSH <?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div style="margin-top: 20px;">
            <?php if($order_data['status'] == 'pending'): ?>
                <a href="cancel_order.php?id=<?php echo $order_id; ?>" 
                   onclick="return confirm('Are you sure you want to cancel order #<?php echo $order_id; ?>?')"
                   style="background: #dc3545; color: white; padding: 10px 20px; border-radius: 30px; text-decoration: none; margin-right: 10px;">
                   ❌ Cancel Order
                </a>
            <?php endif; ?>
            <a href="orders.php" class="back-link">← Back to Orders</a>
        </div>
    </div>
</body>
</html>