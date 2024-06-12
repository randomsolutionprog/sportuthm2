<?php
session_start();
//database sync

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
    <body >
        <?php
        include('../component/top_nav.php');
        include('../component/toast.php');

        ?>
        <!---Page Title--->
        <div class="container-fluid" style="background-color: #00004d;">
            <div class="container d-flex justify-content-center align-items-center" style="height: 30vh;">
                <div class="text-center">
                <h1 class="text-light"><strong>ADMIN PAGE:</strong></h1>
                    <h2 class="text-light">Booking Details For Football Field </h2>
                </div>
            </div>
        </div>
        <?php
        include('../conn.php');

        // Define the number of records per page
        $records_per_page = 10;

        // Determine the current page
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $current_page = (int) $_GET['page'];
        } else {
            $current_page = 1;
        }

        // Calculate the offset for the query
        $offset = ($current_page - 1) * $records_per_page;

        // SQL query to get total number of records
        $total_sql = "SELECT COUNT(*) FROM booking_field";
        $total_result = $conn->query($total_sql);
        $total_row = $total_result->fetch_row();
        $total_records = $total_row[0];

        // Calculate total number of pages
        $total_pages = ceil($total_records / $records_per_page);

        // SQL query to fetch the required records
        $sql = "SELECT 
                    booking_field.booking_id,
                    booking_field.timeSlot,
                    booking_field.date,
                    user.userName
                FROM 
                    booking_field
                LEFT JOIN 
                    user 
                ON 
                    booking_field.userMatrix = user.userMatrix
                ORDER BY 
                    booking_field.date DESC
                LIMIT $records_per_page OFFSET $offset";
        
        $result = $conn->query($sql);
        ?>

        <div class="container my-4">
            <div class="row">
                <div class="col-12 shadow">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Timeslot</th>
                                <th>Date</th>
                                <th>User Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1;
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $i . "</td>";
                                    echo "<td>" . $row["timeSlot"] . "</td>";
                                    echo "<td>" . $row["date"] . "</td>";
                                    echo "<td>" . $row["userName"] . "</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr style='height:18vh;'><td colspan='4' class='text-center'>No bookings found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php
                            if ($current_page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '">Previous</a></li>';
                            }

                            for ($page = 1; $page <= $total_pages; $page++) {
                                if ($page == $current_page) {
                                    echo '<li class="page-item active"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                                }
                            }

                            if ($current_page < $total_pages) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '">Next</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
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