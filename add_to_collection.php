<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once "db_connect.php";

$vinyl_id = $_POST['vinyl_id'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO user_vinyls (user_id, vinyl_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $vinyl_id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>