<?php
session_start();
if(!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    mysqli_query($conn, "INSERT INTO products (name, brand, size, price, stock_quantity) VALUES ('$name', '$brand', $size, $price, $stock)");
    header("Location: products.php");
    exit();
}

if(isset($_POST['update'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    mysqli_query($conn, "UPDATE products SET name='$name', brand='$brand', size=$size, price=$price, stock_quantity=$stock WHERE product_id=$id");
    header("Location: products.php");
    exit();
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE product_id=$id");
    header("Location: products.php");
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products");
$edit_product = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id=$id");
    $edit_product = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products - Wan Shoes</title>
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
        
        <h1>Manage Products</h1>
        
        <?php if($edit_product): ?>
            <h3>Edit Product</h3>
            <form method="POST" onsubmit="return validateProduct()">
                <input type="hidden" name="product_id" value="<?php echo $edit_product['product_id']; ?>">
                <input type="text" id="prod_name" name="name" value="<?php echo $edit_product['name']; ?>" placeholder="Product Name" required>
                <input type="text" id="prod_brand" name="brand" value="<?php echo $edit_product['brand']; ?>" placeholder="Brand" required>
                <input type="number" id="prod_size" name="size" value="<?php echo $edit_product['size']; ?>" placeholder="Size" required>
                <input type="number" step="0.01" id="prod_price" name="price" value="<?php echo $edit_product['price']; ?>" placeholder="Price" required>
                <input type="number" id="prod_stock" name="stock" value="<?php echo $edit_product['stock_quantity']; ?>" placeholder="Stock" required>
                <button type="submit" name="update">Update Product</button>
                <a href="products.php" style="margin-left: 10px;">Cancel</a>
            </form>
        <?php else: ?>
            <h3>Add New Product</h3>
            <form method="POST" onsubmit="return validateProduct()">
                <input type="text" id="prod_name" name="name" placeholder="Product Name" required>
                <input type="text" id="prod_brand" name="brand" placeholder="Brand" required>
                <input type="number" id="prod_size" name="size" placeholder="Size" required>
                <input type="number" step="0.01" id="prod_price" name="price" placeholder="Price (KSH)" required>
                <input type="number" id="prod_stock" name="stock" placeholder="Initial Stock" required>
                <button type="submit" name="add">Add Product</button>
            </form>
        <?php endif; ?>
        
        <h3>Product List</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th><th>Name</th><th>Brand</th><th>Size</th><th>Price</th><th>Stock</th><th>Actions</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['brand']; ?></td>
                <td><?php echo $row['size']; ?></td>
                <td>KSH <?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock_quantity']; ?></td>
                <td><a href="?edit=<?php echo $row['product_id']; ?>">Edit</a> | <a href="?delete=<?php echo $row['product_id']; ?>" onclick="return confirmDelete('<?php echo $row['name']; ?>')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <br>
        <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="admin.php" class="back-link">← Back to Admin Dashboard</a>
        <?php else: ?>
            <a href="dashboard.php" class="back-link">← Back to Employee Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>