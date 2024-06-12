<?php
//database sync
session_start(); // Start the session
// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error'] = "Please log in to your account";
    header("Location: ../index.php");
    exit();
}
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
    // Check if admin is logged in

    // Validate and sanitize input
    if(isset($_GET['matrix']) && !empty($_GET['matrix'])) {
        $matrix = $_GET['matrix'];
        
        // Include database connection
        include('../conn.php');
        
        // Prepare and execute the update query
        $stmt = $conn->prepare("UPDATE user SET adminMatrix = ? WHERE userMatrix = ?");
        $stmt->bind_param('ss', $_SESSION["admin_matrix"], $matrix);
        
        if($stmt->execute()) {
            // If the update was successful, redirect back to the previous page or to a success page
            $_SESSION['success'] = "User Successfully Approved!!";
            header("Location: adminAdd.php");
            exit();
        } else {
            // If the update failed, handle the error
            $_SESSION['error'] = "Error: " . $conn->error;
            header("Location: adminAdd.php");
            exit();
           
        }
        
        // Close the database connection
        $stmt->close();
        $conn->close();
    } else {
        // Handle invalid input
        $_SESSION['error'] = "Invalid user matrix value";
        header("Location: adminAdd.php");
        exit();
    }
} else {
    // Redirect to login page if admin is not logged in
    header("Location: login.php");
    exit();
}
?>
