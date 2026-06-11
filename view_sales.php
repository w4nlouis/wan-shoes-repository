<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

$sales = mysqli_query($conn, "SELECT * FROM sales");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sales</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard">
        <h1>All Sales</h1>
        
        <table border="1">
            <tr><th>ID</th><th>Product ID</th><th>Employee ID</th><th>Quantity</th><th>Date</th></tr>
            <?php while($row = mysqli_fetch_assoc($sales)): ?>
            <tr>
                <td><?php echo $row['sale_id']; ?></td>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['employee_id']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['sale_date']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="admin.php">Back</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>