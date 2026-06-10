<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'clerk') {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

$error = "";
$success = "";

if(isset($_POST['sell'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $employee_id = $_SESSION['user_id'];
    
    $check = mysqli_query($conn, "SELECT stock_quantity FROM products WHERE product_id=$product_id");
    $stock = mysqli_fetch_assoc($check);
    
    if($quantity <= $stock['stock_quantity']) {
        mysqli_query($conn, "INSERT INTO sales (product_id, employee_id, quantity) VALUES ($product_id, $employee_id, $quantity)");
        mysqli_query($conn, "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id=$product_id");
        $success = "Sale recorded!";
    } else {
        $error = "Only " . $stock['stock_quantity'] . " available!";
    }
}

$products = mysqli_query($conn, "SELECT * FROM products WHERE stock_quantity > 0");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Sale - Wan Shoes</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none; font-size: 14px; font-weight: 500;">🚪 Logout</a>
        </div>
        
        <h1>Record Sale</h1>
        
        <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
        <?php if($success) echo "<p style='color:green'>$success</p>"; ?>
        
        <form method="POST" onsubmit="return validateSale()">
            <select id="sale_product" name="product_id" required>
                <option value="">Select Product</option>
                <?php while($p = mysqli_fetch_assoc($products)): ?>
                <option value="<?php echo $p['product_id']; ?>">
                    <?php echo $p['name'] . " - KSH " . $p['price'] . " (Stock: " . $p['stock_quantity'] . ")"; ?>
                </option>
                <?php endwhile; ?>
            </select>
            <br><br>
            <input type="number" id="sale_qty" name="quantity" placeholder="Quantity" required>
            <br><br>
            <button type="submit" name="sell">Record Sale</button>
        </form>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>