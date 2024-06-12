<?php
//database sync
session_start();

// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

// Include the database connection
include("conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matricNo = trim($_POST['matricNo']);
    $password = trim($_POST['password']);
    $loginButton = (int)$_POST['loginButton'];

    if (!empty($matricNo) && !empty($password)) {
        if ($loginButton === 2) {
            // Admin login
            $stmt = $conn->prepare("SELECT adminPass, adminSalt FROM admin WHERE adminMatrix = ?");
        } elseif ($loginButton === 1) {
            // User login
            $stmt = $conn->prepare("
                SELECT user.userPass, user.userSalt 
                FROM user 
                LEFT JOIN email_verification ON user.userMatrix = email_verification.userMatrix 
                WHERE user.adminMatrix IS NOT NULL 
                AND user.userMatrix = ? 
                AND email_verification.verified_date IS NOT NULL
            ");
        } else {
            // Invalid login button value
            $error_message = "Invalid login attempt";
            exit();
        }

        $stmt->bind_param("s", $matricNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, verify password
            $row = $result->fetch_assoc();
            $storedPassword = $loginButton === 2 ? $row['adminPass'] : $row['userPass'];
            $storedSalt = $loginButton === 2 ? $row['adminSalt'] : $row['userSalt'];
            $passwordWithSalt = $password . $storedSalt;

            if (password_verify($passwordWithSalt, $storedPassword)) {
                // Password is correct, set session and redirect
                if ($loginButton === 2) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_matrix'] = $matricNo;
                    header("Location: admin/dashboardAdmin.php");
                    exit();
                } elseif ($loginButton === 1) {
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_matrix'] = $matricNo;
                    header("Location: user/dashboardUser.php");
                    exit();
                }
            } else {
                // Password is incorrect
                $error_message = "Incorrect password";
            }
        } else {
            // User not found
            $error_message = "User not found or User is not verified by Admin";
        }

        $stmt->close();
        $conn->close();
    } else {
        // Empty fields
        $error_message = "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sport Facilities Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
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
                <?php include("component/toast.php"); ?>
                <div class="col-lg-6 col-sm-8 p-4 rounded" style="background-color: #EAEAEA;">
                    <form action="" method="post">
                        <img src="img/uthm.png" class="img-fluid"><br><br>
                        <div class="mb-2">
                            <label for="matricNo" class="text-purple fw-bold text-start">Matric No:</label><br>
                            <input type="text" name="matricNo" id="matricNo" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="text-purple fw-bold">Password:</label><br>
                            <input type="password" name="password" id="password" class="form-control" required><br>
                        </div>
                        <div class="mb-5 mb-md-4 d-block d-md-flex justify-content-between">
                            <div class="d-block mb-2">
                                <input type="checkbox" onclick="togglePassword()"> Show Password
                            </div>
                            <div class="d-block mb-2">
                                Forgotten Password? <a href="forgot.php">Click Here</a><br>
                                No Account? <a href="register.php">Register Here</a>
                            </div>
                        </div>
                        <div class="mb-2 text-center">
                            <label for="remember-me" class="text-purple fw-bold mb-2">Login As</label><br>
                            <button class="btn btn-purple" style="width: 100px" type="submit" name="loginButton" value="1">User</button>
                            <button class="btn btn-purple" style="width: 100px" type="submit" name="loginButton" value="2">Admin</button><br><br>
                        </div>
                        <?php
                        if (isset($error_message)) {
                            echo "<div style='text-align: center;'><h2 style='color: #D53467; font-size: 24px'>{$error_message}</h2></div><br>";
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>
