<?php
session_start();
require_once 'db_connect.php'; // Include database connection

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Delete product
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    // Redirect back to the manage product page
    header("Location: manage_product.php");
    exit();
}
?>
