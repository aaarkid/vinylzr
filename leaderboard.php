<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once "db_connect.php";

// Fetch top vinyls by play count for the current user
$stmt = $conn->prepare("SELECT v.title, v.artist, COUNT(ph.id) AS play_count
                        FROM play_history ph
                        JOIN vinyls v ON ph.vinyl_id = v.id
                        WHERE ph.user_id = ?
                        GROUP BY v.id
                        ORDER BY play_count DESC
                        LIMIT 10");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$top_vinyls = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch top artists by play count for the current user
$stmt = $conn->prepare("SELECT v.artist, COUNT(ph.id) AS play_count
                        FROM play_history ph
                        JOIN vinyls v ON ph.vinyl_id = v.id
                        WHERE ph.user_id = ?
                        GROUP BY v.artist
                        ORDER BY play_count DESC
                        LIMIT 10");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$top_artists_by_plays = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Fetch top artists by vinyl count for the current user
$stmt = $conn->prepare("SELECT v.artist, COUNT(v.id) AS vinyl_count
                        FROM vinyls v
                        JOIN user_vinyls uv ON v.id = uv.vinyl_id
                        WHERE uv.user_id = ?
                        GROUP BY v.artist
                        HAVING COUNT(v.id) > 0
                        ORDER BY vinyl_count DESC
                        LIMIT 10");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$top_artists_by_vinyls = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard | Vinyl Collector</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        function showSection(sectionId) {
            const sections = ['top-vinyls', 'top-artists-plays', 'top-artists-vinyls'];
            sections.forEach(id => {
                document.getElementById(id).style.display = id === sectionId ? 'block' : 'none';
            });

            const buttons = ['btn-top-vinyls', 'btn-top-artists-plays', 'btn-top-artists-vinyls'];
            buttons.forEach(id => {
                document.getElementById(id).classList.toggle('active', id === `btn-${sectionId}`);
            });
        }
    </script>
</head>
<body onload="showSection('top-vinyls')">
    <header>
        <h1 class="site-title">Vinylzr</h1>
        <button class="login-signup-btn" onclick="window.location.href='logout.php'">Logout</button>
    </header>
    <main class="main">
        <h2 class="vinyl-title">Vinyls Wrapped</h2>

        <div class="leaderboard-buttons">
            <button id="btn-top-vinyls" class="leaderboard-buttons button" onclick="showSection('top-vinyls')">Top Vinyls by Play Count</button>
            <button id="btn-top-artists-plays" class="leaderboard-buttons button" onclick="showSection('top-artists-plays')">Top Artists by Play Count</button>
            <button id="btn-top-artists-vinyls" class="leaderboard-buttons button" onclick="showSection('top-artists-vinyls')">Top Artists by Vinyl Count</button>
        </div>

        <div id="top-vinyls" style="display: none;">
            <h3>Top Vinyls by Play Count</h3>
            <ol>
                <?php foreach ($top_vinyls as $vinyl): ?>
                    <li><?= htmlspecialchars($vinyl['title']) ?> - <?= htmlspecialchars($vinyl['artist']) ?> (<?= htmlspecialchars($vinyl['play_count']) ?> plays)</li>
                <?php endforeach; ?>
            </ol>
        </div>

        <div id="top-artists-plays" style="display: none;">
            <h3>Top Artists by Play Count</h3>
            <ol>
                <?php foreach ($top_artists_by_plays as $artist): ?>
                    <li><?= htmlspecialchars($artist['artist']) ?> (<?= htmlspecialchars($artist['play_count']) ?> plays)</li>
                <?php endforeach; ?>
            </ol>
        </div>

        <div id="top-artists-vinyls" style="display: none;">
            <h3>Top Artists by Vinyl Count</h3>
            <ol>
                <?php foreach ($top_artists_by_vinyls as $artist): ?>
                    <li><?= htmlspecialchars($artist['artist']) ?> (<?= htmlspecialchars($artist['vinyl_count']) ?> vinyls)</li>
                <?php endforeach; ?>
            </ol>
        </div>
        <br>
        <button class="nav-btn-page" onclick="window.location.href='dashboard.php'">Back to dashboard</button>
    </main>
</body>


