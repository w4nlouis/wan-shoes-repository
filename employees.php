<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "wan_shoes_db");

if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    mysqli_query($conn, "INSERT INTO employees (name, role, email, password) VALUES ('$name', '$role', '$email', '$password')");
    header("Location: employees.php");
    exit();
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM employees WHERE employee_id=$id");
    header("Location: employees.php");
    exit();
}

$employees = mysqli_query($conn, "SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Employees</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <div class="dashboard">
        <h1>Manage Employees</h1>
        
        <h3>Add New Employee</h3>
        <form method="POST" onsubmit="return validateEmployee()">
            <input type="text" id="emp_name" name="name" placeholder="Full Name" required>
            <select id="emp_role" name="role">
                <option value="">Select Role</option>
                <option value="clerk">Clerk (Sales)</option>
                <option value="stock_manager">Stock Manager</option>
            </select>
            <input type="email" id="emp_email" name="email" placeholder="Email" required>
            <input type="password" id="emp_password" name="password" placeholder="Password (min 8 chars)" required>
            <div id="password-strength" style="margin-bottom: 10px; font-size: 12px;"></div>
            <button type="submit" name="add">Add Employee</button>
        </form>
        
        <h3>Existing Employees</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th><th>Name</th><th>Role</th><th>Email</th><th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($employees)): ?>
            <tr>
                <td><?php echo $row['employee_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><a href="?delete=<?php echo $row['employee_id']; ?>" onclick="return confirmDelete('<?php echo $row['name']; ?>')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <br>
        <a href="admin.php">← Back</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>