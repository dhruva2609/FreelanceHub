<?php
require '../../auth.php';

if ($_SESSION['user_role'] !== 'client') {
    header("Location: ../../unauthorized.html");
    exit();
}

readfile("../client-dashboard.html");
?>
