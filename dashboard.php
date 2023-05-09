<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

$display_name = $_SESSION['display_name'];

// Database connection
require_once 'db_connect.php';

// Fetch user's vinyl collection
$user_id = $_SESSION['user_id'];

// Get sorting option from GET request
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'title_asc';

// Set the ORDER BY clause based on the selected sorting option
switch ($sort_by) {
    case 'title_asc':
        $order_by = "v.title ASC";
        break;
    case 'title_desc':
        $order_by = "v.title DESC";
        break;
    case 'plays':
        $order_by = "play_count DESC";
        break;
    case 'year':
    default:
        $order_by = "v.year DESC";
        break;
}

$sql = "SELECT v.id, v.title, v.cover_image, v.year, COUNT(ph.id) as play_count
        FROM vinyls v
        JOIN user_vinyls uv ON v.id = uv.vinyl_id
        LEFT JOIN play_history ph ON v.id = ph.vinyl_id
        WHERE uv.user_id = ?
        GROUP BY v.id
        ORDER BY $order_by";
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
    <title>Dashboard | Vinyl Collector</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        function addPlay(vinyl_id) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_play.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const playButton = document.querySelector('.vinyl-item-play[data-id="' + vinyl_id + '"]');
                    playButton.classList.add("play-added");
                    setTimeout(() => {
                        playButton.classList.remove("play-added");
                    }, 400);
                }
            }
            xhr.send("vinyl_id=" + encodeURIComponent(vinyl_id));
        }
    </script>

</head>
<body>
    <header>
        <h1 class="site-title">Vinylzr</h1>
        <button class="login-signup-btn" onclick="window.location.href='logout.php'">Logout</button>
    </header>
    <main class="main">
        <h2>Welcome, <?php echo htmlspecialchars($display_name); ?>!</h2><br>
        <p>Your Vinyl Collection:</p><br>
        <div class="dropdown">
            <button class="nav-btn-page dropdown-toggle">Sort</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="?sort_by=title_asc">Title (A-Z)</a>
                <a class="dropdown-item" href="?sort_by=title_desc">Title (Z-A)</a>
                <a class="dropdown-item" href="?sort_by=plays">Plays</a>
                <a class="dropdown-item" href="?sort_by=year">Most Recent</a>
            </div>
        </div>
        <div class="vinyl-collection">
            <?php foreach ($vinyls as $vinyl): ?>
                <div class="vinyl-item">
                    <img src="<?php echo htmlspecialchars($vinyl['cover_image']); ?>" alt="<?php echo htmlspecialchars($vinyl['title']); ?>">
                    <p class="vinyl-item-title"><a href="vinyl_details.php?id=<?php echo htmlspecialchars($vinyl['id']); ?>"><?php echo htmlspecialchars($vinyl['title']); ?></a></p>
                    <div class="vinyl-item-play" data-id="<?php echo htmlspecialchars($vinyl['id']); ?>" onclick="addPlay(<?php echo htmlspecialchars($vinyl['id']); ?>)">+</div>
                </div>
            <?php endforeach; ?>
        </div>

        <br><br>
        <button class="nav-btn-page" onclick="window.location.href='add_vinyl.php'">Add Vinyl</button>
        <button class="nav-btn-page" onclick="window.location.href='search_vinyl.php'">Search Vinyls</button>
        <button class="nav-btn-page" onclick="window.location.href='search_users.php'">Search Users</button>
        <button class="nav-btn-page" onclick="window.location.href='leaderboard.php'">Vinyls Wrapped</button>
        <button class="nav-btn-page" onclick="generateShareableLink()">Share Your Collection</button>
        <script>
            function generateShareableLink() {
                var userId = <?php echo $_SESSION['user_id']; ?>;
                var url = window.location.origin + '/vinyls/public_dashboard.php?user_id=' + userId;
                alert(url);
            }
        </script>

    </main>
</body>
</html>
