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

// Include additional components
include '../component/modules.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sport Facilities Booking</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/style.css?=<?php echo time(); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php include('../component/top_nav.php'); ?>

        <!---Page Title--->
        <div class="container-fluid" style="margin:0; padding: 0;">
            <img src="../img/mainpage.jpeg" class="fixed-height" style="height: 300px !important;">
        </div>
        <div class="container-fluid" style="background-color: #00004d;">
            <div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">
                <div class="text-center">
                    <h1 class="text-light"><strong>What UTHM Sport Facilities Has?</strong></h1>
                    <h4 class="text-light text-montserrat">
                        We are committed to providing the best services and sports equipment facilities for campus citizens.
                        The sports and recreational facilities provided by the university are Gym, football field, netball and badminton court.
                        In addition, UTHM Campus Pagoh's community can use Share Facilities that provided by Edu Hub Pagoh in order to meet residents needs.
                    </h4>
                </div>
            </div>
        </div>

        <!---List Of Facilities--->
        <div class="container my-4">
            <div class="row mb-3">
                <h1>Booking Option</h1>
            </div>
            <div class="row">
                <!-- Card 1 -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow">
                        <img src="../img/field.png" class="card-img-top fixed-height" alt="Football Field">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-montserrat">Football Field</h5><br>
                            <a href="bookingField.php" class="btn btn-dark">Book</a>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow">
                        <img src="../img/court.jpeg" class="card-img-top fixed-height" alt="Court">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-montserrat">Court</h5><br>
                            <a href="bookingCourt.php" class="btn btn-dark">Book</a>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow">
                        <img src="../img/gym1.jpeg" class="card-img-top fixed-height" alt="Gymnasium">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-montserrat">Gymnasium</h5><br>
                            <a href="bookingGym.php" class="btn btn-dark">Book</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
    <?php include('../component/footer.php'); ?>
</html>
