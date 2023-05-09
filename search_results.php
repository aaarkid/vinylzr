<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once "db_connect.php";

$search_query = '%' . strtolower($_POST['search_query']) . '%';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM vinyls WHERE LOWER(title) LIKE ? OR LOWER(artist) LIKE ?");
$stmt->bind_param('ss', $search_query, $search_query);
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Vinyl Collector</title>
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
        <h2 class="vinyl-title">Search Results</h2>
        <?php if ($results->num_rows > 0): ?>
            <ul class="search-results-list">
            <?php while ($result = $results->fetch_assoc()): ?>
                <li class="search-result-item">
                <h3 class="search-result-title">
                    <?= htmlspecialchars($result['title']) ?> - <?= htmlspecialchars($result['artist']) ?>
                    <span class="search-result-year"><?= htmlspecialchars($result['year']) ?></span>
                </h3>
                    <form action="add_to_collection.php" method="post">
                        <input type="hidden" name="vinyl_id" value="<?= $result['id'] ?>">
                        <input type="submit" value="Add to Collection" class="nav-btn-page">
                    </form>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="vinyl-text">No results found for "<?= htmlspecialchars($search_query) ?>".</p>
        <?php endif; ?>
        <button class="nav-btn-page" onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
    </main>
</body>
</html>
