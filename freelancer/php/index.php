<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'freelancer') {
    header("Location: ../login.html");
    exit();
}
?>
<h1>Welcome, Freelancer!</h1>
<a href="../common/logout.php">Logout</a>
