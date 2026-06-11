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
    
    mysqli_query($conn, "UPDATE products SET stock_quantity = stock_quantity + $quantity WHERE product_id=$product_id");
    $success = "Stock added!";
}

$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Stock</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <div class="dashboard">
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
        <a href="dashboard.php">← Back</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>