<?php
include 'component/modules.php';

session_start();
if(isset($_GET['token'])){
    $_SESSION['token'] =  $_GET['token'];
}
if(isset($_GET['matrix'])){
    $_SESSION['matrix'] =  $_GET['matrix'];
}

// Include the database connection
include('conn.php');

// Process the password change
if (isset($_POST["change"])) {
    $token = $_SESSION["token"];
    $matrix = $_SESSION["matrix"];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Check if passwords match
    if ($password != $password_confirm) {
        $_SESSION["error"] = "Passwords do not match";
        header("Location: verifyUser_password.php?token=$token&matrix=$matrix");
        exit();
    }

    // Prepare and execute the SELECT query with LEFT JOIN
    $query = "SELECT * FROM `user` LEFT JOIN `email_verification` ON user.userMatrix = email_verification.userMatrix WHERE email_verification.token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the token exists in the email_verification table
    if ($result->num_rows > 0) {
        // Token exists, proceed with password update
        $updatePasswordQuery = "UPDATE user SET userPass = ?, userSalt = ? WHERE userMatrix = ?";
        $stmtUpdatePassword = $conn->prepare($updatePasswordQuery);

        // Generate a random salt
        $salt = generateSalt();

        // Append the salt to the password
        $password_with_salt = $password . $salt;

        // Hash the password with salt
        $hashedPassword = password_hash($password_with_salt, PASSWORD_DEFAULT);

        $stmtUpdatePassword->bind_param("sss", $hashedPassword, $salt, $matrix);
        if ($stmtUpdatePassword->execute()) {
            // Password updated successfully
            // Set token to null in email_verification table
            $updateTokenQuery = "UPDATE email_verification SET token = NULL WHERE userMatrix = ?";
            $stmtUpdateToken = $conn->prepare($updateTokenQuery);
            $stmtUpdateToken->bind_param("s", $matrix);
            $stmtUpdateToken->execute();

            $_SESSION["success"] = "Password updated successfully.";
            header("Location: index.php");
            exit();
        } else {
            // Error updating password
            $_SESSION["error"] = "Error updating password: " . $stmtUpdatePassword->error;
            header("Location: index.php");
            exit();
        }
    } else {
        // Token does not exist, redirect to index.php with error message
        $_SESSION["error"] = "Invalid verification token.";
        header("Location: index.php");
        exit();
    }

    // Close prepared statements and database connection
    $stmtUpdatePassword->close();
    $stmt->close();
    $conn->close();
} elseif (!isset($_GET['token']) || !isset($_GET['matrix'])) {
    header('Location: index.php');
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
        <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var password_confirm = document.getElementById("password_confirm").value;
            var passwordPattern = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{8,}$/;

            if (!passwordPattern.test(password)) {
                alert("Password must be at least 8 characters long, contain at least one special character, and at least one number.");
                return false;
            }

            if (password !== password_confirm) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }
    </script>
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
                        
                    
                        <div class="nav-bar d-flex justify-content-center mb-3">
                            <a href="index.php"><img src="img/uthm.png"class="img-fluid" style="height:50px;"></a>
                        </div>
                        <form action="verifyUser_password.php" method="POST" id="forgot_password" onsubmit="return validatePassword()">

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Password Confirm</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            <div class="mb-3">
                                Remember Password ?<a href="logout.php"> Click Here</a>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="change">Change Password</button>
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

