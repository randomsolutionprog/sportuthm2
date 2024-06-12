<?php
session_start();

//databse sync

// Include the database connection
include('conn.php');

// Check if token and matrix are set in the URL
if (isset($_GET['token']) && isset($_GET['matrix'])) {
    // Retrieve token and matrix from the URL
    $token = $_GET['token'];
    $matrix = $_GET['matrix'];

    // Prepare and execute the UPDATE query to set verified_date
    $updateQuery = "UPDATE email_verification SET verified_date = NOW() WHERE token = ? AND userMatrix = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $token, $matrix);
    $stmt->execute();

    // Check if the UPDATE query was successful
    if ($stmt->affected_rows > 0) {
        // Prepare and execute the UPDATE query to set token to null
        $nullifyTokenQuery = "UPDATE email_verification SET token = NULL WHERE userMatrix = ?";
        $stmtNullify = $conn->prepare($nullifyTokenQuery);
        $stmtNullify->bind_param("s", $matrix);
        $stmtNullify->execute();
        $stmtNullify->close();

        // Verification successful
        $_SESSION["success"] = "Email verified successfully. Please wait for admin approval in 3 business days.";
        header("Location: index.php");
        exit();
    } else {
        // Verification failed (invalid token or matrix)
        $_SESSION["error"] = "Invalid verification token or matrix number.";
        header("Location: index.php");
        exit();
    }
} else {
    // Token or matrix not set in the URL
    $_SESSION["error"] = "Invalid verification request.";
    header("Location: index.php");
    exit();
}

// Close the database connection
$stmt->close();
$conn->close();
?>
