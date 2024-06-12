<?php
// Database connection
$servername = "localhost";
$username = "root";
$dbPassword = "";
$dbname = "sportuthm";

$conn = new mysqli($servername, $username, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>