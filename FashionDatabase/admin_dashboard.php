<?php
session_start();
require_once 'db_connect.php'; // Include database connection

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Redirect to login page if not logged in as admin
    header("Location: login.php");
    exit();
}

// Delete user
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/admin_dashboard.css" />
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h2, h3 {
            color: #444;
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #2c3e50;
            padding-top: 20px;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            display: block;
            font-weight: 500;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .content h1 {
            font-size: 28px;
        }

        /* Form Styles */
        form {
            background-color: #fff;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 8px 0;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:hover {
            background-color: #ecf0f1;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logout {
            text-align: right;
            margin-top: 20px;
        }

        .logout a {
            color: #e74c3c;
        }

        .logout a:hover {
            color: #c0392b;
        }

        .add-user-btn {
            margin: 10px 0;
            text-align: flex;
        }

        .add-user-btn button {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .add-user-btn button:hover {
            background-color: #2980b9;
        }

        /* Edit and Delete Buttons */
        .action-btn {
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

     <!-- Sidebar -->
     <div class="sidebar">
        <h2 style="color: #fff; text-align: center;">Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="adduser.php">Add New User</a> 
        <a href="manage_product.php">Manage Product</a> <!-- Link to Add User -->
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Welcome, Admin</h1>
        <p>This is your admin dashboard.</p>
        
        <h2>Manage Users</h2>
            <!-- Add New User Button -->
            <div class="add-user-btn">
                <a href="addUser.php">
                    <button type="button">Add New User</button>
                </a>
            </div>
        
        <!-- Users Table -->
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <a href="editUser.php?edit_user_id=<?php echo $row['id']; ?>">
                                <button class="action-btn">Edit</button>
                            </a>
                            <a href="admin_dashboard.php?delete_user_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                                <button class="action-btn delete-btn">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
