<?php
session_start();
// echo $_SESSION['admin_logged_in'];
// echo $_SESSION['admin_matrix'] ;
session_destroy();
header("Location: index.php");

?>