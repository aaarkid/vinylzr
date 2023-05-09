<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vinyl</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
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
            <h2 class="vinyl-title">Add Vinyl</h2>
            <form action="insert_vinyl.php" method="post">
                <label for="vinyl_title">Title:</label>
                <input type="text" id="vinyl_title" name="vinyl_title" required>
                
                <label for="vinyl_artist">Artist:</label>
                <input type="text" id="vinyl_artist" name="vinyl_artist" required>
                
                <label for="vinyl_year">Year:</label>
                <input type="text" id="vinyl_year" name="vinyl_year" required>
                
                <label for="vinyl_image">Image URL:</label>
                <input type="text" id="vinyl_image" name="vinyl_image">
                
                <input type="submit" value="Add Vinyl" class="login-signup-btn">
            </form>
        </div>
        <button class="nav-btn-page return-to-dashboard" onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
    </main>
</body>
</html>
