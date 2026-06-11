<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'stock_manager') {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

$success = "";

if(isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $employee_id = $_SESSION['user_id'];
    
    mysqli_query($conn, "UPDATE products SET stock_quantity = stock_quantity + $quantity WHERE product_id=$product_id");
    mysqli_query($conn, "INSERT INTO stock_records (product_id, employee_id, quantity_added) VALUES ($product_id, $employee_id, $quantity)");
    $success = "Stock added!";
}

$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Stock - Wan Shoes</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;">🚪 Logout</a>
        </div>
        
        <h1>Add Stock</h1>
        
        <?php if($success) echo "<p style='color:green'>$success</p>"; ?>
        
        <form method="POST" onsubmit="return validateStock()">
            <select id="stock_product" name="product_id" required>
                <option value="">Select Product</option>
                <?php while($p = mysqli_fetch_assoc($products)): ?>
                <option value="<?php echo $p['product_id']; ?>">
                    <?php echo $p['name'] . " - Current Stock: " . $p['stock_quantity']; ?>
                </option>
                <?php endwhile; ?>
            </select>
            <br><br>
            <input type="number" id="stock_qty" name="quantity" placeholder="Quantity to Add" required>
            <br><br>
            <button type="submit" name="add">Add Stock</button>
        </form>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>