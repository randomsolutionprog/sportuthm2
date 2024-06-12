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
           .center {
                width: 80px;
                height: 80px;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            hr {
                border: 6px black solid;
            }
        </style>
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
                    <h4 class="text-light text-montserrat">We are committed to providing the best services and sports equipment facilities for campus citizens. The sports and recreational facilities provided by the university are Gym, football field, netball, and badminton court. In addition, UTHM Campus Pagoh's community can use Share Facilities that provided by Edu Hub Pagoh in order to meet residents' needs.</h4>
                </div>
            </div>
        </div>

        <!---List Of Facilities--->
        <div class="container my-4">
            <div class="row mb-3">
                <h1>My Booking</h1>
            </div>
            <!--Booking Football Field--->
            <div class="row">
                <?php
                include("../conn.php");
                $user = $_SESSION['user_matrix'];
                // Prepare the SQL statement with placeholders
                $sql = "SELECT * FROM booking_field WHERE userMatrix = ? AND date >= CURRENT_DATE ORDER BY date ASC";

                // Initialize the statement
                $stmt = $conn->prepare($sql);

                // Check if the statement was prepared successfully
                if ($stmt === false) {
                    die('Prepare failed: ' . $conn->error);
                }

                // Bind the parameters to the placeholders (assuming userMatrix is a string)
                $stmt->bind_param('s', $user);

                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                ?>

                <div class="container my-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Booking Football Field</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0) { ?>
                                <?php while ($row = mysqli_fetch_array($result)) { ?>
                                    <tr>
                                        <td>
                                            <img src="../img/reserve.png" class="center" />
                                        </td>
                                        <td>
                                            <?php
                                            echo "Timeslot: " . $row['timeSlot'] . "<br>";
                                            echo "No Matric: " . $row['userMatrix'] . "<br>";
                                            echo "Date: " . $row['date'];
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="poprintField.php?date=<?php echo $row['date']; ?>" class="btn btn-primary">Slip</a>
                                                <a href="deleteField.php?date=<?php echo $row['date']; ?>&timeslot=<?php echo $row['timeSlot']; ?>" class="btn btn-danger  cancel-btn">Cancel</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" class="text-center">No bookings available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <!--Booking Court--->
            <div class="row mb-3">
                <?php
                include("../conn.php");
                $user = $_SESSION['user_matrix'];
                // Prepare the SQL statement with placeholders
                $sql = "SELECT * FROM booking_court WHERE userMatrix = ? AND date >= CURRENT_DATE ORDER BY date ASC";

                // Initialize the statement
                $stmt = $conn->prepare($sql);

                // Check if the statement was prepared successfully
                if ($stmt === false) {
                    die('Prepare failed: ' . $conn->error);
                }

                // Bind the parameters to the placeholders (assuming userMatrix is a string)
                $stmt->bind_param('s', $user);

                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                ?>

                <div class="container my-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Booking Court</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0) { ?>
                                <?php while ($row = mysqli_fetch_array($result)) { ?>
                                    <tr>
                                        <td>
                                            <img src="../img/reserve.png" class="center" />
                                        </td>
                                        <td>
                                            <?php
                                            echo "Timeslot: " . $row['timeSlot'] . "<br>";
                                            echo "No Matric: " . $row['userMatrix'] . "<br>";
                                            echo "Date: " . $row['date'];
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="poprintCourt.php?date=<?php echo $row['date']; ?>" class="btn btn-primary">Slip</a>
                                                <a href="deleteCourt.php?date=<?php echo $row['date']; ?>&timeslot=<?php echo $row['timeSlot']; ?>" class="btn btn-danger  cancel-btn">Cancel</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" class="text-center">No bookings available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <!--Booking Gym--->
            <div class="row mb-3">
                <?php
                include("../conn.php");
                $user = $_SESSION['user_matrix'];
                // Prepare the SQL statement with placeholders
                $sql = "SELECT * FROM booking_gym WHERE userMatrix = ? AND date >= CURRENT_DATE ORDER BY date ASC";

                // Initialize the statement
                $stmt = $conn->prepare($sql);

                // Check if the statement was prepared successfully
                if ($stmt === false) {
                    die('Prepare failed: ' . $conn->error);
                }

                // Bind the parameters to the placeholders (assuming userMatrix is a string)
                $stmt->bind_param('s', $user);

                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();
                ?>

                <div class="container my-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Booking Gym</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0) { ?>
                                <?php while ($row = mysqli_fetch_array($result)) { ?>
                                    <tr>
                                        <td>
                                            <img src="../img/reserve.png" class="center" />
                                        </td>
                                        <td>
                                            <?php
                                            echo "Timeslot: " . $row['timeSlot'] . "<br>";
                                            echo "No Matric: " . $row['userMatrix'] . "<br>";
                                            echo "Date: " . $row['date'];
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="poprintGym.php?date=<?php echo $row['date']; ?>" class="btn btn-primary">Slip</a>
                                                <a href="deleteGym.php?date=<?php echo $row['date']; ?>&timeslot=<?php echo $row['timeSlot']; ?>" class="btn btn-danger  cancel-btn">Cancel</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" class="text-center">No bookings available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirm Cancellation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel this booking?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger " id="confirmButton">Yes</button>
                    </div>
                </div>
            </div>
        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
        
            <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                let hrefToCancel;

                function showCustomConfirm(element) {
                    hrefToCancel = element.getAttribute('href');
                    console.log(hrefToCancel);
                    const confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                    confirmModal.show();
                    return false; // Prevent default link action
                }

                document.querySelectorAll('.cancel-btn').forEach(btn => {
                    btn.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent the default link behavior
                        showCustomConfirm(this); // Show the confirmation dialog
                    });
                });

                document.getElementById('confirmButton').addEventListener('click', function () {
                    window.location.href = hrefToCancel; // Proceed with the cancellation
                });
            });
            </script>

    </body>
    <?php include('../component/footer.php'); ?>
</html>
