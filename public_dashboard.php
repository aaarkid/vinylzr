<?php
session_start();

// Check if the user_id is provided
if (!isset($_GET['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Database connection
require_once 'db_connect.php';

// Fetch user's display name
$user_id = $_GET['user_id'];
$sql = "SELECT display_name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$display_name = $user['display_name'];

// Fetch user's vinyl collection
$sql = "SELECT v.id, v.title, v.cover_image, COUNT(ph.id) AS play_count
        FROM vinyls v
        JOIN user_vinyls uv ON v.id = uv.vinyl_id
        LEFT JOIN play_history ph ON v.id = ph.vinyl_id
        WHERE uv.user_id = ?
        GROUP BY v.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$vinyls = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Dashboard | Vinyl Collector</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1 class="site-title">Vinylzr</h1>
        <button class="login-signup-btn" onclick="window.location.href='logout.php'">Logout</button>
    </header>
    <main class="main">
        <h2><?php echo htmlspecialchars($display_name); ?>'s Vinyl Collection</h2>
        <div class="vinyl-collection">
            <?php foreach ($vinyls as $vinyl): ?>
                <div class="vinyl-item">
                    <img src="<?php echo htmlspecialchars($vinyl['cover_image']); ?>" alt="<?php echo htmlspecialchars($vinyl['title']); ?>">
                    <p class="vinyl-item-title"><?php echo htmlspecialchars($vinyl['title']); ?></p>
                    <p class="vinyl-item-play-count">Plays: <?php echo htmlspecialchars($vinyl['play_count']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <br><br><br>
        <button class="nav-btn-page" onclick="window.location.href='dashboard.php'">Back to dashboard</button>
    </main>
</body>
</html>
