<?php
session_start();

include 'component/modules.php';

// Validate and process form submission
if(isset($_POST['verify'])) {
    $email = $_POST['email'];

    // Generate a new verification token
    $newToken = generateToken();

    include('conn.php');

    // Retrieve matrix information from user table by email
    $getUserQuery = "SELECT userMatrix FROM user WHERE userEmail = ?";
    $stmtGetUser = $conn->prepare($getUserQuery);
    $stmtGetUser->bind_param("s", $email);
    $stmtGetUser->execute();
    $resultGetUser = $stmtGetUser->get_result();

    if ($resultGetUser->num_rows > 0) {
        // Fetch the matrix information
        $row = $resultGetUser->fetch_assoc();
        $matrix = $row['userMatrix'];

        // Update the email table with the new token
        $updateQuery = "UPDATE  email_verification SET token = ? WHERE userMatrix = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("ss", $newToken, $matrix);

        if ($stmtUpdate->execute()) {
            // Send email for verification
            if(sendEmail($email, $newToken, $matrix)) {
                unset($_SESSION['emailToVerify']);
                $_SESSION["success"] = "Email verification sent successfully! Please check your email for verification.";
                header("Location: register.php");
                exit();
            } else {
                $_SESSION["error"] = "Error sending verification email.";
                header("Location: register.php");
                exit();
            }
        } else {
            $_SESSION["error"] = "Error updating email: " . $stmtUpdate->error;
            header("Location: register.php");
            exit();
        }

        $stmtUpdate->close();
    } else {
        // If no user found with the provided email
        $_SESSION["error"] = "No user found with the provided email.";
        header("Location: register.php");
        exit();
    }

    $stmtGetUser->close();
    $conn->close();
}
if(isset($_POST['register'])) {
    // Validate form inputs (you can add more validation as needed)
    $matricNo = strtolower($_POST['matricNo']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $noTel = $_POST['noTel'];

    if(empty($matricNo) || empty($password) || empty($name) || empty($email) || empty($noTel)) {
        $_SESSION["error"] = "All fields are required";
        header("Location: register.php");
        exit();
    }

    // Check if passwords match
    if($password != $password_confirm) {
        $_SESSION["error"] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }

    // Generate a random salt
    $salt = generateSalt();

    // Append the salt to the password
    $password_with_salt = $password.$salt;

    // Hash the password with salt
    $hashedPassword = password_hash($password_with_salt, PASSWORD_DEFAULT);
   
    include('conn.php');


    // Check if the email is already being used
    $checkEmailQuery = "SELECT * FROM user WHERE userEmail = ?";
    $stmtCheckEmail = $conn->prepare($checkEmailQuery);
    $stmtCheckEmail->bind_param("s", $email);
    $stmtCheckEmail->execute();
    $resultCheckEmail = $stmtCheckEmail->get_result();

    if ($resultCheckEmail->num_rows > 0) {
        // Email is already being used, redirect back with an error message
        $_SESSION["emailToVerify"] = $email;
        $_SESSION["error"] = "Email is already being used";
        header("Location: register.php");
        exit();
    }
    // $_SESSION['password']=$password;
    // $_SESSION['salt']=$salt;

    // Insert user data into the user table
    $sql = "INSERT INTO user (userMatrix, userPass, userName, userEmail, userNoTel, userSalt) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $matricNo, $hashedPassword, $name, $email, $noTel, $salt);
    if ($stmt->execute()) {
        // User data inserted successfully, insert into email table
        $verificationToken = generateToken(); // Generate a unique verification token
        $sqlEmail = "INSERT INTO email_verification (token, userMatrix) VALUES (?, ?)";
        $stmtEmail = $conn->prepare($sqlEmail);
        $stmtEmail->bind_param("ss", $verificationToken, $matricNo);
        if ($stmtEmail->execute()) {
            // Email data inserted successfully, send email for verification
            if(sendEmail($email, $verificationToken, $matricNo)) {
                $_SESSION["success"] = "Registration successful! Please check your email for verification.";
                header("Location: register.php");
                exit();
            } else {
                $_SESSION["error"] = "Error sending verification email.";
                header("Location: register.php");
                exit();
            }
        } else {
            $_SESSION["error"] =  "Error inserting email data: " . $stmtEmail->error;
            header("Location: register.php");
            exit();
        }
        $stmtEmail->close();
    } else {
        $_SESSION["error"] =  "Error inserting user data: " . $stmt->error;
        header("Location: register.php");
        exit();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
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
                        if(!isset($_SESSION["emailToVerify"])){
                        ?>
                    
                        <div class="d-flex justify-content-center mb-3">
                            <a href="index.php"><img src="img/uthm.png"class="img-fluid" style="height:50px;"></a>
                            
                        </div>
                        <form action="register.php" method="POST" id="register_account" onsubmit="return validatePassword()">
                            <div class="mb-3">
                                <label for="matricNo" class="form-label">Matric Number</label>
                                <input type="text" class="form-control" id="matricNo" name="matricNo" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Password Confirm</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="noTel" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="noTel" name="noTel" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="register">Register</button>
                            </div>
                        </form>
                        <?php
                        }
                        else{
                            $emailToVerify = filter_var($_SESSION["emailToVerify"], FILTER_VALIDATE_EMAIL);
                        ?>
                        <div class="nav-bar d-flex justify-content-center mb-3">
                            <a href="index.php"><img src="img/uthm.png"class="img-fluid" style="height:50px;"></a>
                        </div>
                        <form action="register.php" method="POST" id="verify_email">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $emailToVerify;?>" readonly>
                            </div>
                            <div class="mb-3">
                                Email Verified?<a href="logout.php"> Click Here</a>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="verify">Send Verification</button>
                            </div>
                        </form>
                        <?php
                        }


                        if (isset($error_message)) {
                            echo "<div style='text-align: center;'><h2 style='color: #D53467; font-size: 24px'>{$error_message}</h2></div><br>";
                        }
                        ?>
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
