<?php
session_start();
//database sync
// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $_SESSION['error'] = "Please log in to your account.";
    header("Location: ../index.php");
    exit();
}

include("../conn.php");

$user = $_SESSION['user_matrix'];
$date = $_GET['date'];
$timeslot = $_GET['timeslot'];

$sql = "DELETE FROM booking_field WHERE userMatrix = ? AND timeSlot = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user, $timeslot, $date);

if ($stmt->execute()) {
    $_SESSION["success"] = "Record deleted successfully";
    header('location: mybooking.php');
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>