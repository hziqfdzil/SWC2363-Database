<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    $errors = [];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Username already taken";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Email already registered";
    }

    // Password validation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - Fashion</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/register.css" />
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3>Register Here</h3>

        <label for="username">Username</label>
        <input type="text" placeholder="Username" id="username" name="username" required />

        <label for="email">Email</label>
        <input type="email" placeholder="Email" id="email" name="email" required />

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password" required />

        <label for="confirm_password">Confirm Password</label>
        <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required />

        <button type="submit">Register</button>

        <div class="social">
            <div class="go"><i class="fab fa-google"></i> Google</div>
            <div class="fb"><i class="fab fa-facebook"></i> Facebook</div>
        </div>

        <p style="text-align: center; margin-top: 20px;">
            Already have an account? <a href="login.php" style="color: #fff;">Login here</a>
        </p>
    </form>
</body>

</html>