<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header("Location: cart.php");
    exit();
}

// Remove from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

// Clear cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

// Get cart items
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['product_id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<title>Shopping Cart - Wan Shoes</title>
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
        
        <h1>Shopping Cart</h1>
        
        <?php if (empty($cart_items)): ?>
            <p style="text-align: center; padding: 40px;">Your cart is empty. <a href="products.php">Browse products</a></p>
        <?php else: ?>
            <table border="1" cellpadding="10" style="width: 100%;">
                <tr>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['brand']; ?></td>
                        <td>KSH <?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <a href="?add=<?php echo $item['product_id']; ?>" style="text-decoration: none;">➕</a>
                            <?php echo $item['quantity']; ?>
                            <a href="?remove=<?php echo $item['product_id']; ?>" style="text-decoration: none; color: red;">➖</a>
                        </td>
                        <td>KSH <?php echo number_format($item['subtotal'], 2); ?></td>
                        <td><a href="?remove=<?php echo $item['product_id']; ?>" style="color: red; text-decoration: none;"> Remove</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; font-size: 18px;">
                    <td colspan="4" style="text-align: right;">Total:</td>
                    <td>KSH <?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
            </table>
            
            <div style="display: flex; gap: 15px; margin-top: 20px; justify-content: center;">
                <a href="checkout.php" style="padding: 12px 30px; background: #2ecc71; color: white; text-decoration: none; border-radius: 30px;"> Proceed to Checkout</a>
                <a href="?clear=1" style="padding: 12px 30px; background: #dc3545; color: white; text-decoration: none; border-radius: 30px;"> Clear Cart</a>
                <a href="products.php" style="padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 30px;"> Continue Shopping</a>
            </div>
        <?php endif; ?>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>