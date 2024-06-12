<?php
session_start();

include 'component/modules.php';
?>
<?php
if (isset($_POST['verify'])) {
    include("conn.php");
    // Get the email from the form
    $email = trim($_POST['email']);

    if (!empty($email)) {
        // Prepare and execute the SELECT query to check if the email exists in the database
        $checkEmailQuery = "SELECT userMatrix FROM user WHERE userEmail = ?";
        $stmtCheckEmail = $conn->prepare($checkEmailQuery);
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $resultCheckEmail = $stmtCheckEmail->get_result();

        if ($resultCheckEmail->num_rows > 0) {
            // Fetch the matrix number of the user
            $userData = $resultCheckEmail->fetch_assoc();
            $matrixNo = $userData['userMatrix'];

            // Generate a verification token
            $verificationToken = generateToken();

            // Prepare and execute the UPDATE query to update the token in the email_verification table
            $updateQuery = "UPDATE email_verification SET token = ? WHERE userMatrix = ?";
            $stmtUpdateToken = $conn->prepare($updateQuery);
            $stmtUpdateToken->bind_param("ss", $verificationToken, $matrixNo);
            $stmtUpdateToken->execute();

            // Check if the token update was successful
            if ($stmtUpdateToken->affected_rows > 0) {
                // Send email with the verification token for password reset
                if (sendResetEmail($email, $verificationToken, $matrixNo)) {
                    $_SESSION["success"] = "Email change password sent successfully! Please check your email.";
                } else {
                    $_SESSION["error"] = "Error sending password reset email.";
                }
            } else {
                $_SESSION["error"] = "Error updating verification token.";
            }

            $stmtUpdateToken->close();
        } else {
            // Email does not exist in the database
            $_SESSION["error"] = "No user found with the provided email.";
        }

        $stmtCheckEmail->close();
    } else {
        $_SESSION["error"] = "Please provide a valid email address.";
    }

    $conn->close();

    // Redirect to forgot.php
    header("Location: forgot.php");
    exit();
}
?>
<?php
if (isset($_POST['verify'])) {
    include("conn.php");
    // Get the email from the form
    $email = trim($_POST['email']);

    if (!empty($email)) {
        // Prepare and execute the SELECT query to check if the email exists in the database
        $checkEmailQuery = "SELECT userMatrix FROM user WHERE userEmail = ?";
        $stmtCheckEmail = $conn->prepare($checkEmailQuery);
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $resultCheckEmail = $stmtCheckEmail->get_result();

        if ($resultCheckEmail->num_rows > 0) {
            // Fetch the matrix number of the user
            $userData = $resultCheckEmail->fetch_assoc();
            $matrixNo = $userData['userMatrix'];

            // Generate a verification token
            $verificationToken = generateToken();

            // Prepare and execute the UPDATE query to update the token in the email_verification table
            $updateQuery = "UPDATE email_verification SET token = ? WHERE userMatrix = ?";
            $stmtUpdateToken = $conn->prepare($updateQuery);
            $stmtUpdateToken->bind_param("ss", $verificationToken, $matrixNo);
            $stmtUpdateToken->execute();

            // Check if the token update was successful
            if ($stmtUpdateToken->affected_rows > 0) {
                // Send email with the verification token for password reset
                if (sendResetEmail($email, $verificationToken, $matrixNo)) {
                    $_SESSION["success"] = "Email change password sent successfully! Please check your email.";
                } else {
                    $_SESSION["error"] = "Error sending password reset email.";
                }
            } else {
                $_SESSION["error"] = "Error updating verification token.";
            }

            $stmtUpdateToken->close();
        } else {
            // Email does not exist in the database
            $_SESSION["error"] = "No user found with the provided email.";
        }

        $stmtCheckEmail->close();
    } else {
        $_SESSION["error"] = "Please provide a valid email address.";
    }

    $conn->close();

    // Redirect to forgot.php
    header("Location: forgot.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sport Facilities Booking</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
            crossorigin="anonymous">
            <link rel="stylesheet" href="css/style.css?<?php echo time(); ?>">


        <style>
            #background {
                background-image: url("img/uthm.jpg");
                background-repeat: no-repeat;
                height: 100vh;
            }
           
        </style>
    </head>
    <body>
        
        <div class="container-fluid position-relative" id="background">
     
            <div class="container position-absolute top-50 start-50 translate-middle">
               
                <div class="d-flex justify-content-center position-relative">
                    
                    <div class="col-lg-6 col-sm-8 p-4 rounded" style="background-color: #EAEAEA;">
                        <?php
                        // if(isset($_SESSION['error'])) {
                        //     $errorMessage = $_SESSION['error'];
                        //     echo "<p class='bg-danger text-white w-auto position-absolute' style='top:10vh; right: 10vw;'>$errorMessage</p>";
                        // }
                        include("component/toast.php");
                        ?>
                        <form action="forgot.php" method="POST" id="register_account">
                        
                    
                        <div class="nav-bar d-flex justify-content-center mb-3">
                            <a href="index.php"><img src="img/uthm.png"class="img-fluid" style="height:50px;"></a>
                        </div>
                        <form action="register.php" method="POST" id="verify_email">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                Remember Password ?<a href="logout.php"> Click Here</a>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="verify">Send Verification</button>
                            </div>
                        </form>
                            
                 
                    </div>
                </div>
            </div>
        </div>
        
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

       
    </body>
</html>
