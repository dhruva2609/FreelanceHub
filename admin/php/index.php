<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'client') {
    header("Location: ../login.html");
    exit();
}
?>
<h1>Welcome, Client!</h1>
<a href="../common/logout.php">Logout</a>
