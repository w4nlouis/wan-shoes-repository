<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$error = "";
$success = "";

// Process checkout
if (isset($_POST['checkout'])) {
    $customer_id = $_SESSION['customer_id'];
    $total = 0;
    
    // Calculate total
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");
    $cart_items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['product_id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $cart_items[] = $row;
    }
    
    // Insert order
    $sql = "INSERT INTO orders (customer_id, total_amount) VALUES ($customer_id, $total)";
    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);
        
        // Insert order items
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)");
            
            // Update stock
            mysqli_query($conn, "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $product_id");
        }
        
        // Clear cart
        $_SESSION['cart'] = [];
        $success = "✅ Order placed successfully! Order #$order_id";
    } else {
        $error = "Checkout failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<title>Checkout - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <div>
                <span style="margin-right: 20px; color: #666;">Welcome, <?php echo $_SESSION['customer_name']; ?></span>
                <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;"> Logout</a>
            </div>
        </div>
        
        <h1>Checkout</h1>
        
        <?php if($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div style="background: #e8f5e9; padding: 20px; border-radius: 10px; text-align: center;">
                <p style="font-size: 24px; color: green;"><?php echo $success; ?></p>
                <p style="margin-top: 20px;"><a href="dashboard.php" style="padding: 10px 20px; background: #000; color: white; text-decoration: none; border-radius: 30px;">Go to Dashboard</a></p>
            </div>
        <?php else: ?>
            <p style="margin-bottom: 20px;">Review your order before placing it.</p>
            
            <table border="1" cellpadding="10" style="width: 100%;">
                <tr>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                <?php 
                $total = 0;
                $ids = implode(',', array_keys($_SESSION['cart']));
                $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");
                while($item = mysqli_fetch_assoc($result)):
                    $quantity = $_SESSION['cart'][$item['product_id']];
                    $subtotal = $item['price'] * $quantity;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['brand']; ?></td>
                        <td>KSH <?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td>KSH <?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr style="font-weight: bold; font-size: 18px;">
                    <td colspan="4" style="text-align: right;">Total:</td>
                    <td>KSH <?php echo number_format($total, 2); ?></td>
                </tr>
            </table>
            
            <form method="POST" style="margin-top: 20px; text-align: center;">
                <button type="submit" name="checkout" style="background: #2ecc71; color: white; padding: 12px 30px; border: none; border-radius: 30px; cursor: pointer; font-size: 16px;">✅ Place Order</button>
                <a href="cart.php" style="padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 30px;">← Back to Cart</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>