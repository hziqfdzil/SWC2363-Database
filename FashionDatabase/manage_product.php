<?php
session_start();
require_once 'db_connect.php'; // Include database connection

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch required product details from the database
$stmt = $conn->prepare("SELECT id, name, quantity, price FROM products");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/admin_dashboard.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #2c3e50;
            color: #fff;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        td a {
            color: #3498db;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_product.php">Add New Product</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Manage Products</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <a href="edit_product.php?product_id=<?php echo $product['id']; ?>">Edit</a> |
                            <a href="delete_product.php?product_id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
