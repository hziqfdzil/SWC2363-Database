<?php
session_start();
require_once 'db_connect.php'; // Include database connection

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Redirect to login page if not logged in as admin
    header("Location: login.php");
    exit();
}

// Fetch user data to edit
if (isset($_GET['edit_user_id'])) {
    $user_id = $_GET['edit_user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    // If no user_id is provided, redirect to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();
}

// Update user information
if (isset($_POST['edit_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password if changed
    $hashed_password = empty($password) ? $user['password'] : password_hash($password, PASSWORD_DEFAULT);

    if (empty($password)) {
        // If password is not changed, update without changing the password
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $user_id);
    } else {
        // Update username, email, password, and role
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $email, $hashed_password, $role, $user_id);
    }
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <style>
        /* General Body Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #2c3e50;
            padding-top: 20px;
            color: #fff;
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

        .sidebar a.active {
            background-color: #3498db;
        }

        /* Main Content Styles */
        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Form Styles */
        form {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input:focus, select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 4px rgba(52, 152, 219, 0.5);
        }

        button {
            background-color: #3498db;
            color: #fff;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
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

            .sidebar a {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="adduser.php">Add New User</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Edit User</h1>

        <form action="edituser.php?edit_user_id=<?php echo $user_id; ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="password">Password (Leave empty to keep unchanged):</label>
            <input type="password" id="password" name="password">

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>

            <button type="submit" name="edit_user">Save Changes</button>
        </form>
    </div>

</body>
</html>
