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

include '../component/modules.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sport Facilities Booking</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/style.css?=".<?php echo time();?>>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>
            @media print {
            .no-print {
                display: none;
            }
        }
           
        </style>
    </head>
    <body>
        <?php
        //include('../component/top_nav.php');
        ?>
        <div class="container my-5 border p-3">
            <div class="text-center mb-4">
                <img src="../img/logo.png" class="img-fluid" style="max-width: 50%;">
            </div>
            <?php
            include("../conn.php");
            $user = $_SESSION['user_matrix'];
            $date = $_GET['date'];
            $sql = "SELECT booking_field.booking_id, booking_field.timeSlot, booking_field.userMatrix, booking_field.date, user.userName, user.userMatrix, user.userNoTel
                    FROM booking_field
                    INNER JOIN user ON user.userMatrix = booking_field.userMatrix
                    WHERE booking_field.userMatrix = ? AND booking_field.date >= CURRENT_DATE";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                echo '<h1>Football Field</h1><br>';
                echo '<h2>SUCCESSFUL!</h2>';
                echo '<h2>YOUR BOOKING HAS BEEN APPROVED</h2><br><br>';
                echo '<h3>NAME: ' . strtoupper($row['userName']) . '</h3>';
                echo '<h3>MATRIC NO: ' . strtoupper($row['userMatrix']) . '</h3>';
                echo '<h3>PHONE NO: 0' . $row['userNoTel'] . '</h3>';
                echo '<h3>DATE OF USE: ' . $date . '</h3>';
            } else {
                echo '<h3>No booking found.</h3>';
            }

            $stmt->close();
            $conn->close();
            ?>
            
            <div class="mt-5 text-center no-print">
                <a href="mybooking.php" class="btn btn-danger">Close</a>
                <button class="btn btn-secondary" onclick="window.print()">Print</button>
            </div>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFconnIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

       
    </body>
    <?php
    //include('../component/footer.php');
    ?>
</html>