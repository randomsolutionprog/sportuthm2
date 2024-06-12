<?php
session_start();
//database sync
// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);
date_default_timezone_set('Asia/Kuala_Lumpur');

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
        <link rel="stylesheet" href="../css/style.css?i=<?php echo time(); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>
            .today {
                background-color: yellow !important;
            }
        </style>
    </head>
    <body>
        <?php include('../component/top_nav.php'); ?>
        <!---Page Title--->
        <div class="container-fluid" style="background-color: #00004d;">
            <div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">
                <div class="text-center">
                    <h1 class="fw-bold text-light" style="font-size: 50px !important;">Booking</h1>
                    <h4 class="text-light text-montserrat">Gym</h4>
                </div>
            </div>
        </div>

        <!-- List Of Facilities -->
        <div class="container my-4">
            <div class="row">
                <h1>Booking-Calendar</h1>
            </div>
            <div class="row">
                <div class="container mt-4">
                    <div class="alert alert-info">
                        <strong>Info!</strong> The system will show available dates for only three weeks from the current date.
                    </div>

                    <div class="col-md-12">
                        <?php
                        function build_calendar($month, $year) {
                            include("../conn.php");
                            $stmt = $conn->prepare("SELECT * FROM booking_gym WHERE MONTH(date) = ? AND YEAR(date) = ?");
                            $stmt->bind_param('ss', $month, $year);
                            $bookings = array();
                            if ($stmt->execute()) {
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $bookings[] = $row['date'];
                                    }
                                    $stmt->close();
                                }
                            }

                            $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                            $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
                            $numberDays = date('t', $firstDayOfMonth);
                            $dateComponents = getdate($firstDayOfMonth);
                            $monthName = $dateComponents['month'];
                            $dayOfWeek = $dateComponents['wday'];
                            $datetoday = new DateTime();

                            $calendar = "<div class='table-responsive'>";
                            $calendar .= "<table class='table table-bordered'>";
                            $calendar .= "<thead>";
                            $calendar .= "<tr>";

                            foreach ($daysOfWeek as $day) {
                                $calendar .= "<th class='header'>$day</th>";
                            }

                            $calendar .= "</tr>";
                            $calendar .= "</thead>";
                            $calendar .= "<tbody><tr>";

                            if ($dayOfWeek > 0) {
                                for ($k = 0; $k < $dayOfWeek; $k++) {
                                    $calendar .= "<td class='empty'></td>";
                                }
                            }

                            $month = str_pad($month, 2, "0", STR_PAD_LEFT);
                            $currentDay = 1;

                            while ($currentDay <= $numberDays) {
                                if ($dayOfWeek == 7) {
                                    $dayOfWeek = 0;
                                    $calendar .= "</tr><tr>";
                                }

                                $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
                                $date = "$year-$month-$currentDayRel";
                                $dayname = strtolower(date('l', strtotime($date)));
                                $eventNum = 0;
                                $today = $date == date('Y-m-d') ? "today" : "";

                                if ($dayname == 'friday' || $dayname == "saturday") {
                                    $calendar .= "<td class='$today'><h4>$currentDay</h4>";
                                } elseif ($date < date('Y-m-d')) {
                                    $calendar .= "<td class='$today'><h4>$currentDay</h4>";
                                } elseif ($date > date('Y-m-d', strtotime('+3 weeks'))) {
                                    $calendar .= "<td class='$today'><h4>$currentDay</h4>";
                                } else {
                                    $totalbookings = checkSlots($conn, $date);
                                    if ($totalbookings == 3) {
                                        $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='#' class='btn btn-danger btn-sm'>ALL BOOKED</a>";
                                    } elseif ($totalbookings == 2) {
                                        $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='popGym.php?date=" . $date . "' class='btn btn-warning btn-sm'>BOOK</a>";
                                    } elseif ($totalbookings == 1) {
                                        $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='popGym.php?date=" . $date . "' class='btn btn-info btn-sm'>BOOK</a>";
                                    } else {
                                        $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='popGym.php?date=" . $date . "' class='btn btn-success btn-sm'>BOOK</a>";
                                    }
                                }

                                $calendar .= "</td>";
                                $currentDay++;
                                $dayOfWeek++;
                            }

                            if ($dayOfWeek != 7) {
                                $remainingDays = 7 - $dayOfWeek;
                                for ($l = 0; $l < $remainingDays; $calendar .= "<td class='empty'></td>", $l++);
                            }

                            $calendar .= "</tr>";
                            $calendar .= "</tbody>";
                            $calendar .= "</table>";
                            $calendar .= "</div>";

                            echo $calendar;
                        }

                        function checkSlots($conn, $date) {
                            $stmt = $conn->prepare("SELECT * FROM booking_gym WHERE date = ?");
                            $stmt->bind_param('s', $date);
                            $totalbookings = 0;
                            if ($stmt->execute()) {
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $totalbookings++;
                                    }
                                    $stmt->close();
                                }
                            }
                            return $totalbookings;
                        }

                        $dateComponents = getdate();
                        if (isset($_GET['month']) && isset($_GET['year'])) {
                            $month = $_GET['month'];
                            $year = $_GET['year'];
                        } else {
                            $month = $dateComponents['mon'];
                            $year = $dateComponents['year'];
                        }

                        // Calculate previous and next month and year
                        $prev_month = $month == 1 ? 12 : $month - 1;
                        $prev_year = $month == 1 ? $year - 1 : $year;
                        $next_month = $month == 12 ? 1 : $month + 1;
                        $next_year = $month == 12 ? $year + 1 : $year;

                        // Display current month and navigation buttons
                        echo '<div class="d-flex justify-content-between align-items-center mb-3">';
                        echo '<a href="?month=' . $prev_month . '&year=' . $prev_year . '" class="btn btn-primary">&lt; Previous</a>';
                        echo '<h2 class="mb-0">' . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . '</h2>';
                        echo '<a href="?month=' . $next_month . '&year=' . $next_year . '" class="btn btn-primary">Next &gt;</a>';
                        echo '</div>';

                        echo build_calendar($month, $year);
                        ?>
                    </div>

                    <div class="text-center mt-4">
                        <strong>AVAILABLE SLOTS</strong>
                        <br><br>
                        <div class="d-flex justify-content-center">
                            <table class="table table-bordered w-50">
                                <thead>
                                    <tr>
                                        <th>3 slots</th>
                                        <th>2 slots</th>
                                        <th>1 slot</th>
                                        <th>0 slots</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><div class="bg-success" style="width: 20px; height: 20px;"></div></td>
                                        <td class="text-center"><div class="bg-info" style="width: 20px; height: 20px;"></div></td>
                                        <td class="text-center"><div class="bg-warning" style="width: 20px; height: 20px;"></div></td>
                                        <td class="text-center"><div class="bg-danger" style="width: 20px; height: 20px;"></div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
    <?php include('../component/footer.php'); ?>
</html>
