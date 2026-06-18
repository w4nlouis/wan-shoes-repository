<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['id'] ?? 0;
$customer_id = $_SESSION['customer_id'];

// Check if order exists and belongs to this customer
$check = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = $order_id AND customer_id = $customer_id");
if (mysqli_num_rows($check) == 0) {
    header("Location: orders.php");
    exit();
}

$order = mysqli_fetch_assoc($check);

// Only allow cancellation if status is 'pending'
if ($order['status'] != 'pending') {
    $_SESSION['error'] = "Order #$order_id cannot be cancelled because it's already " . $order['status'] . ".";
    header("Location: order_details.php?id=$order_id");
    exit();
}

// Cancel the order
mysqli_query($conn, "UPDATE orders SET status = 'cancelled' WHERE order_id = $order_id");

// Restore stock
$items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $order_id");
while ($item = mysqli_fetch_assoc($items)) {
    mysqli_query($conn, "UPDATE products SET stock_quantity = stock_quantity + " . $item['quantity'] . " WHERE product_id = " . $item['product_id']);
}

$_SESSION['success'] = "Order #$order_id has been cancelled successfully.";
header("Location: orders.php");
exit();
?>