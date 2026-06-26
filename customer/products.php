<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Handle search
$search_term = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    $products = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%$search_term%' OR brand LIKE '%$search_term%' ORDER BY name ASC");
} else {
    $products = mysqli_query($conn, "SELECT * FROM products ORDER BY name ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products - Wan Shoes</title>
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
        
        <h1>Our Shoes</h1>
        <div class="subtitle">Browse our collection</div>
        
        <!-- Search Form -->
        <form method="GET" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Search by name or brand..." 
                       value="<?php echo htmlspecialchars($search_term); ?>"
                       style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                <button type="submit" style="padding: 12px 30px; background: #000; color: white; border: none; border-radius: 8px;"> Search</button>
                <?php if($search_term): ?>
                    <a href="products.php" style="padding: 12px 24px; background: #666; color: white; text-decoration: none; border-radius: 8px;">Clear</a>
                <?php endif; ?>
            </div>
        </form>
        
        <!-- Results count -->
        <?php if($search_term): ?>
            <p style="margin-bottom: 20px; color: #666;">Showing results for "<strong><?php echo htmlspecialchars($search_term); ?></strong>"</p>
        <?php endif; ?>
        
        <!-- Products Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 20px;">
            <?php if(mysqli_num_rows($products) == 0): ?>
                <p style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                    <?php echo $search_term ? 'No products found matching "' . htmlspecialchars($search_term) . '"' : 'No products available.'; ?>
                </p>
            <?php else: ?>
                <?php while($row = mysqli_fetch_assoc($products)): ?>
                    <div style="background: #f8f8f8; border-radius: 16px; padding: 20px; text-align: center; border-bottom: 3px solid #d4af37;">
                        <div style="font-size: 48px; margin-bottom: 10px;">👟</div>
                        <h3 style="color: #000;"><?php echo $row['name']; ?></h3>
                        <p style="color: #666;">Brand: <?php echo $row['brand']; ?></p>
                        <p style="color: #666;">Size: <?php echo $row['size']; ?></p>
                        <p style="font-size: 24px; font-weight: bold; color: #d4af37; margin: 10px 0;">KSH <?php echo number_format($row['price'], 2); ?></p>
                        <p style="color: <?php echo ($row['stock_quantity'] > 0) ? 'green' : 'red'; ?>;">
                            <?php echo ($row['stock_quantity'] > 0) ? ' In Stock (' . $row['stock_quantity'] . ')' : ' Out of Stock'; ?>
                        </p>
                        <?php if($row['stock_quantity'] > 0): ?>
                            <a href="cart.php?add=<?php echo $row['product_id']; ?>" 
                               style="display: inline-block; margin-top: 10px; padding: 10px 30px; background: #000; color: white; text-decoration: none; border-radius: 30px;">
                                Add to Cart
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>