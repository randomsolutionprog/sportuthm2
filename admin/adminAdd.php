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
                    <h2 class="text-light">Add New User</h2>
                </div>
            </div>
        </div>
        <?php
        include('../conn.php'); // Include your database connection

        // Fetch user data where verified_date is not null and matrix_Admin is null
        $sql = "
            SELECT user.userMatrix, user.userName, user.userEmail, email_verification.verified_date
            FROM user 
            LEFT JOIN  email_verification ON user.userMatrix = email_verification.userMatrix
            WHERE email_verification.verified_date IS NOT NULL AND user.adminMatrix IS NULL ORDER BY email_verification.verified_date ASC
        ";
        $result = $conn->query($sql);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }
        ?>

        <div class="container">
            <div class="alert alert-info my-3">
                <strong>Info!</strong> Below is the list of new user that email is verified but needs approval from the admin.
            </div>
            <h2 class="mb-4">User Verification</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Matrix User</th>
                        <th class="d-none d-md-table-cell">Name</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th>Verified Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['userMatrix']); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($row['userName']); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($row['userEmail']); ?></td>
                                <td><?php echo htmlspecialchars($row['verified_date']); ?></td>
                                <td>
                                    <a href="verifyUserAcc.php?matrix=<?php echo htmlspecialchars($row['userMatrix']); ?>" class="btn btn-primary">Verify</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No users to verify</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>

<?php
$conn->close(); // Close the database connection
?>

    
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

       
    </body>
    <?php
    include('../component/footer.php');
    ?>
</html>