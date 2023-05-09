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

// Process the login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $display_name = trim($_POST['display_name']);
    $password = $_POST['password'];

    // Prepare the SQL query
    $sql = "SELECT id, display_name, password FROM users WHERE display_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $display_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user data in session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['display_name'] = $user['display_name'];

            // Redirect to the dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = 'Incorrect password!';
        }
    } else {
        $error_message = 'Display name not found!';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h2 class="vinyl-title">Login</h2>
            <form action="login.php" method="post">
                <label for="display_name">Display Name:</label>
                <input type="text" name="display_name" id="display_name" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <button type="submit">Login</button>
            </form>
            <br>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </main>
</body>
</html>
