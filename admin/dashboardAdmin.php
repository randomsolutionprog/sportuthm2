<?php
session_start();
// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error'] = "Please log in to your account";
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
            <div class="row">
                <!-- Card 1 -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow">
                        <img src="../img/field.png" class="card-img-top fixed-height" alt="...">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-montserrat">Footbal Field</h5><br>
                            <a href="adminField.php" class="btn btn-dark">Click</a>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow">
                        <img src="../img/court.jpeg" class="card-img-top fixed-height" alt="...">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-montserrat">Court</h5><br>
                            <a href="adminCourt.php" class="btn btn-dark">Click</a>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow">
                        <img src="../img/gym1.jpeg" class="card-img-top fixed-height" alt="...">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-montserrat">Gymnasium</h5><br>
                            <a href="adminGym.php" class="btn btn-dark">Click</a>
                        </div>
                    </div>
                </div>
            </div>
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