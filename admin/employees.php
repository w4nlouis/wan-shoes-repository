<?php
session_start();
include '../includes/config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO employees (name, role, email, password) VALUES ('$name', 'manager', '$email', '$password')");
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<title>Manage Managers - Wan Shoes</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div class="logo">WAN SHOES</div>
            <a href="../logout.php" style="background: #dc3545; color: white; padding: 10px 24px; border-radius: 30px; text-decoration: none;"> Logout</a>
        </div>
        
        <h1>Manage Managers</h1>
        
        <h3>Hire New Manager</h3>
        <form method="POST" onsubmit="return validateEmployee()">
            <div class="form-group">
                <input type="text" id="emp_name" name="name" placeholder="Full Name" required>
            </div>
            
            <div class="form-group">
                <input type="email" id="emp_email" name="email" placeholder="Email" required>
            </div>
            
            <div class="form-group" style="position: relative;">
                <input type="password" id="emp_password" name="password" placeholder="Password (min 8 chars)" required>
                <span id="toggleEmpPassword" onclick="togglePassword('emp_password', 'toggleEmpPassword')" 
                      style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px;">
                      
                </span>
            </div>
            
            <div id="password-strength" style="margin-bottom: 15px; font-size: 12px;"></div>
            
            <button type="submit" name="add" style="background: #2ecc71;"> Hire Manager</button>
        </form>
        
        <h3>Current Managers</h3>
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($employees)): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <a href="?delete=<?php echo $row['employee_id']; ?>" 
                       onclick="return fireEmployee('<?php echo $row['name']; ?>')"
                       style="color: #dc3545; text-decoration: none;">
                        Fire
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <br>
        <a href="dashboard.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>