<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once "db_connect.php";

if (isset($_POST['search_query'])) {
    $search_query = '%' . strtolower($_POST['search_query']) . '%';

    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(display_name) LIKE ?");
    $stmt->bind_param('s', $search_query);
    $stmt->execute();
    $results = $stmt->get_result();
} else {
    $results = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users | Vinyl Collector</title>
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
        <h2 class="vinyl-title">Search Users</h2>

        <form action="search_users.php" method="post">
            <input type="text" name="search_query" placeholder="Search users..." value="<?= isset($_POST['search_query']) ? htmlspecialchars($_POST['search_query']) : '' ?>" class="search-box">
            <input type="submit" value="Search" class="nav-btn-page">
        </form>

        <?php if ($results !== null && $results->num_rows > 0): ?>
            <ul class="search-results-list">
            <?php while ($result = $results->fetch_assoc()): ?>
                <li class="search-result-item">
                <h3 class="search-result-title">
                    <?= htmlspecialchars($result['display_name']) ?>
                </h3>
                    <form action="public_dashboard.php" method="get">
                        <input type="hidden" name="user_id" value="<?= $result['id'] ?>">
                        <input type="submit" value="View Dashboard" class="nav-btn-page">
                    </form>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php elseif ($results !== null): ?>
            <p class="vinyl-text">No results found for "<?= htmlspecialchars($_POST['search_query']) ?>".</p>
        <?php endif; ?>
        <br>
        <button class="nav-btn-page" onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
    </main>
</body>
</html>
