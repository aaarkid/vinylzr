<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if vinyl ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$vinyl_id = $_GET['id'];

// Database connection
require_once 'db_connect.php';

// Fetch vinyl details
$sql = "SELECT * FROM vinyls WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vinyl_id);
$stmt->execute();
$result = $stmt->get_result();
$vinyl = $result->fetch_assoc();

if (!$vinyl) {
    header("Location: dashboard.php");
    exit();
}

// Fetch play count
$user_id = $_SESSION['user_id'];
$sql_play_count = "SELECT COUNT(*) as play_count FROM play_history WHERE user_id = ? AND vinyl_id = ?";
$stmt_play_count = $conn->prepare($sql_play_count);
$stmt_play_count->bind_param("ii", $user_id, $vinyl_id);
$stmt_play_count->execute();
$result_play_count = $stmt_play_count->get_result();
$play_count_data = $result_play_count->fetch_assoc();
$play_count = $play_count_data['play_count'];

$stmt->close();
$stmt_play_count->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($vinyl['title']); ?> | Vinyl Collector</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1 class="site-title">Vinyl Collector</h1>
        <button class="login-signup-btn" onclick="window.location.href='logout.php'">Logout</button>
    </header>
    <main class="main">
    <div class="vinyl-details-container">
            <div class="vinyl-display">
                <div class="vinyl-image-container">
                    <img src="<?php echo htmlspecialchars($vinyl['cover_image']); ?>" alt="<?php echo htmlspecialchars($vinyl['title']); ?>" class="vinyl-image">
                </div>
                <div class="vinyl-info">
                    <h2 class="vinyl-detail-title"><?php echo htmlspecialchars($vinyl['title']); ?></h2>
                    <p>Artist: <?php echo htmlspecialchars($vinyl['artist']); ?></p>
                    <p>Year: <?php echo htmlspecialchars($vinyl['year']); ?></p>
                    <p>Plays: <?php echo htmlspecialchars($play_count); ?></p>
                </div>
            </div>
        </div>
        <button class="nav-btn-page return-to-dashboard" onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
    </main>
</body>
</html>