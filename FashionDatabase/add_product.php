<?php
session_start();
require_once 'db_connect.php'; // Include database connection

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Add product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Insert product into database
    $stmt = $conn->prepare("INSERT INTO products (product_id, name, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $product_id, $name, $quantity, $price);
    $stmt->execute();
    header("Location: manage_product.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/admin_dashboard.css" />
    <style>
        /* General Body Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
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

        /* Main Content Styles */
        .content {
            margin-left: 260px;
            padding: 20px;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form Styles */
        form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 210px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_product.php">Manage Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Add New Product</h1>

        <form action="add_product.php" method="POST">
            <label for="product_id">Product ID:</label>
            <input type="text" id="product_id" name="product_id" required>

            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>

            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>
</body>
</html>
