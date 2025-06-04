<?php
$host = "localhost";
$user = "root";  // Default user for XAMPP
$pass = "";      // Default password for XAMPP (empty)
$dbname = "web_project1";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
