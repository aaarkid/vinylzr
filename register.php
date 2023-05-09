<?php
// Start session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Include database connection file
require_once 'db_connect.php';

// Process the registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $display_name = trim($_POST['display_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = 'Passwords do not match!';
    } else {
        // Check if display name already exists
        $sql = "SELECT id FROM users WHERE display_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $display_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Display name already exists!';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (display_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $display_name, $email, $hashed_password);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error_message = 'Error: Could not register the user!';
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1 class="site-title">Vinylizr</h1>
        <div>
            <a href="index.html" class="nav-btn-page">Home</a>
            <a href="about.html" class="nav-btn-page">About</a>
        </div>
    </header>
    <main>
        <div class="form-container">
            <h2 class="vinyl-title">Register</h2>
            <form action="register.php" method="post">
                <label for="display_name">Display Name:</label>
                <input type="text" name="display_name" id="display_name" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <br>
                <button type="submit">Register</button>
            </form>
            <br>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </main>
</body>
</html>
