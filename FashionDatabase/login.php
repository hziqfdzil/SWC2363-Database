<?php
session_start();
require_once 'db_connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check the database for matching username or email
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a user is found
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store user role

            // Redirect based on the user role
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            } else {
                header("Location: index.php"); // Redirect to the homepage for regular users
            }
            exit();
        }
    }
    // If login fails, show an error message
    $error_message = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Fashion</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/login.css" />
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <h3>Login Here</h3>

        <label for="username">Username or Email</label>
        <input type="text" placeholder="Username or Email" id="username" name="username" required />

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password" required />

        <button type="submit">Log In</button>
        <div class="social">
            <div class="go"><i class="fab fa-google"></i> Google</div>
            <div class="fb"><i class="fab fa-facebook"></i> Facebook</div>
        </div>
    </form>
</body>
</html>
