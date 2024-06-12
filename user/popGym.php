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

include('../conn.php');
if(isset($_GET['date'])){
    $date = $_GET['date'];
    $stmt = $conn -> prepare("select*from booking_gym where date = ?");
    $stmt -> bind_param('s', $date);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[]=$row['timeSlot'];
            }
            
            $stmt->close();
        }
    }
}

if(isset($_POST['submit'])){
    $noMatric = $_POST['noMatric'];
    $timeslot = $_POST['timeslot'];
      $stmt = $conn -> prepare("select*from booking_gym where date = ? AND timeSlot = ?");
    $stmt -> bind_param('ss', $date, $timeslot);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $msg = "<div class='alert alert-danger'>Already Booked</div>";
        }else{
             $stmt = $conn->prepare("INSERT INTO booking_gym (timeSlot, userMatrix, date) VALUES (?,?,?)");
             $stmt->bind_param('sss', $timeslot, $noMatric, $date);
             $stmt->execute();
             $msg = "<div class='alert alert-success'>Booking Successfull</div>";
             $bookings[]=$timeslot;
             $stmt->close();
             $conn->close();
            
        }
    }
}
   

$duration  = 180;
$cleanup = 0;
$start ="09:00";
$end = "18:00";

function timeslots($duration, $cleanup, $start, $end){
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT".$duration."M");
    $cleanupInterval = new DateInterval("PT".$cleanup."M");
    $slots=array();
    
    for($intStart = $start; $intStart<$end;$intStart-> add($interval)->add($cleanupInterval)){
        $endPeriod = clone $intStart;
        $endPeriod -> add($interval);
        if($endPeriod>$end){
            break;
        }
        
        $slots[]=$intStart->format("H:iA")."-".$endPeriod->format("H:iA");
    }
    
    return $slots;
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

        <style>
            .popup {
            display: none;
            position: fixed;
            z-index: 101;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: popup 0.5s ease forwards;
        }

        @keyframes popup {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .popup-content {
            background: #f3f3f3;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px #000;
        }
        </style>
    </head>
    <body>
        <?php
        include('../component/top_nav.php');
        ?>
        <!---Page Title--->
        <div class="container-fluid" style="background-color: #00004d;">
            
            <div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">
                <div class="text-center">
                <h1 class="text-light"><strong>What UTHM Sport Facilities Has?</strong></h1>
                    <h4 class="text-light text-montserrat">We are committed to providing the best services and sports equipment facilities for campus citizens. The sports and recreational facilities provided by the university are Gym, football field , netball and badminton court. In addition, UTHM Campus Pagoh's community can use Share Facilities that provided by Edu Hub Pagoh in order to meet residents needs.</h4>
                </div>
            </div>
        </div>

        <div class="container my-4 text-center form-control shadow">
            <!-- Change according to facility name -->
            <h1 class="text-center">Gym</h1>
            <h1 class="text-center">Book For Date: <?php echo date('m/d/Y', strtotime($date)); ?></h1>
            <?php echo isset($msg) ? $msg : ""; ?>
            <?php
            $timeslots = timeslots($duration, $cleanup, $start, $end);
            foreach ($timeslots as $ts) {
            ?>
                <?php if (in_array($ts, $bookings)) { ?>
                    <button class="btn btn-danger mb-2"><?php echo $ts; ?></button>
                <?php } else { ?>
                    <button class="btn btn-success book mb-2" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                <?php } ?>
            <?php } ?>
            <br>
            <br>
            <a href="bookingGym.php" class="btn btn-secondary mb-3">Back</a>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content popup-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Book Gym</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="timeslot" class="form-label">Timeslot</label>
                            <input type="text" readonly name="timeslot" id="timeslot" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="noMatric" class="form-label">No Matric</label>
                            <input type="text" value="<?php echo  $_SESSION['user_matrix']; ?>" name="noMatric" class="form-control" readonly required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <a data-bs-dismiss="modal" class="btn btn-secondary">Back</a>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
    <script>
        $(document).ready(function () {
            $(".book").click(function () {
                var timeslot = $(this).data('timeslot');
                $("#timeslot").val(timeslot);
                $("#bookingModal").modal("show");
            });
        });
    </script>
        

       
    </body>
    <?php
    include('../component/footer.php');
    ?>
</html>