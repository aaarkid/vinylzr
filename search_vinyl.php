<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Vinyl</title>
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1 class="site-title">Vinylizr</h1>
        <form action="logout.php" method="post">
            <input type="submit" value="Logout" class="login-signup-btn">
        </form>
    </header>
    <main>
        <div class="form-container">
            <h2 class="vinyl-title">Search Vinyl</h2>
            <form action="search_results.php" method="post">
                <label for="search_query">Search:</label>
                <input type="text" id="search_query" name="search_query" required>
                
                <input type="submit" value="Search" class="login-signup-btn">
            </form>
        </div>
        <button class="nav-btn-page return-to-dashboard" onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
    </main>
</body>
</html>
