<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "db_connect.php";

    $vinyl_id = $_POST['vinyl_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO play_history (user_id, vinyl_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $vinyl_id);

    if ($stmt->execute()) {
        echo "Play added";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
