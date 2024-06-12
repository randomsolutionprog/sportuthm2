<?php
session_start();
//database sync
// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error'] = "Please log in to your account";
    header("Location: ../index.php");
    exit();
}
include '../component/modules.php';

// Check if the save button is clicked
if(isset($_POST['save'])) {
    include("../conn.php");
    
    // Validate input
    $password = $_POST['password'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Input validation
    if(empty($password) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: adminEdit.php");
        exit();
    }

    $user = $_SESSION['admin_matrix'];

    // Prepare statement
    $stmt = $conn->prepare("SELECT adminPass, adminSalt FROM admin WHERE adminMatrix=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if($stmt->num_rows == 1) {
        $stmt->bind_result($hashedPassword, $salt);
        $stmt->fetch();
        
        // Verify the current password
        if (password_verify($password.$salt, $hashedPassword)) {
            // Check if new password matches the confirm password
            if ($newPassword === $confirmPassword) {
               
                $salt = generateSalt();
                $password_with_salt = $newPassword.$salt;
                // Hash the password with salt
                $hashedNewPassword = password_hash($password_with_salt, PASSWORD_DEFAULT);
              
                // Generate salt and hash the new password
               
                // Update the password in the database
                $updateStmt = $conn->prepare("UPDATE admin SET adminPass=?, adminSalt=? WHERE adminMatrix=?");
                $updateStmt->bind_param("sss", $hashedNewPassword, $salt, $user);
                if($updateStmt->execute()) {
                    $_SESSION['success'] = "Password changed successfully.";
                    header("Location: adminEdit.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to update password. Please try again later.";
                    header("Location: adminEdit.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "New Password and Confirm Password do not match.";
                header("Location: adminEdit.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Current Password is incorrect.";
            header("Location: adminEdit.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found.";
        header("Location: adminEdit.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sport Facilities Booking</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/style.css?=".<?php echo time();?>>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

        <style>
           
           
        </style>
    </head>
    <body>
        <?php
        include('../component/top_nav.php');
        include('../component/toast.php');

        ?>
        <!---Page Title--->
        <div class="container-fluid" style="background-color: #00004d;">
            <div class="container d-flex justify-content-center align-items-center" style="height: 30vh;">
                <div class="text-center">
                <h1 class="text-light"><strong>ADMIN PAGE:</strong></h1>
                    <h2 class="text-light">RECORD OF FACILITIES</h2>
                </div>
            </div>
        </div>

        <!---List Of Facilities--->
        <div class="container my-4">
            <form action="adminEdit.php" method="post">
            <h1>Change Password</h1>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label"><b>Username:</b></label>
                    <input required type="text" value="<?php echo $_SESSION["admin_matrix"];?>" name="noMatric" class="form-control" readonly>
                </div> 
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label"><b>Password:</b></label>
                    <input type="password" name="password" autocomplete="current-password" required="" id="id_password" class="form-control">
                </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label"><b>New Password:</b></label>
                    <input type="password" name="newPassword" autocomplete="current-password" required="" id="id_password1" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label"><b>Confirm Password:</b></label>
                    <input type="password" name="confirmPassword" autocomplete="current-password" required="" id="id_password2" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
            </form>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

       
    </body>
    <?php
    include('../component/footer.php');
    ?>
</html>

