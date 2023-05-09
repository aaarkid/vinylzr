<?php
// db.php
$servername = "localhost";
$username = "vinyls";
$password = "CargoRun--release";
$dbname = "VinylsWrappedDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>