<?php
session_start();

// Include your database connection here
require_once 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['vinyl_title'];
    $artist = $_POST['vinyl_artist'];
    $year = $_POST['vinyl_year'];
    $image = $_POST['vinyl_image'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO vinyls (title, artist, year, cover_image, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $title, $artist, $year, $image, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
