<?php
$url = getCurrentUrl();
$path = parse_url($url, PHP_URL_PATH);
$fileName = basename($path);
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
    $rules = [
        "adminCourt.php" => 1, "adminField.php" => 1, "adminGym.php" => 1, "dashboardAdmin.php" => 1,
        "adminAdd.php" => 2, "adminEdit.php" => 3,
    ];
    $value = null;

    foreach ($rules as $key => $val) {
        if ($key === $fileName) {
            $value = $val;
            break; // Stop the loop once the key is found
        }
    }
    ?>
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../img/uthm.jpeg"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $value == 1 ? 'active' : '' ?>" aria-current="page" href="dashboardAdmin.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $value == 2 ? 'active' : '' ?>" href="adminAdd.php">Add User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $value == 3 ? 'active' : '' ?>" href="adminEdit.php">Change Password</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-md-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
} else if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) {
    $rules = [
        "dashboardUser.php" => 1, "bookingField.php" => 1, "bookingGym.php" => 1, "bookingCourt.php" => 1, "popField.php" => 1, "popCourt.php" => 1, "popGym.php" => 1,
        "mybooking.php" => 2
    ];
    $value = null;

    foreach ($rules as $key => $val) {
        if ($key === $fileName) {
            $value = $val;
            break; // Stop the loop once the key is found
        }
    }
    ?>
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../img/uthm.jpeg"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $value == 1 ? 'active' : '' ?>" aria-current="page" href="dashboardUser.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $value == 2 ? 'active' : '' ?>" href="mybooking.php">Booking List</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Change Password</a>
                    </li> -->
                </ul>
                <ul class="navbar-nav ml-md-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}
?>
