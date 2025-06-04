<?php
$servername = "localhost";
$username = "root";     // or your DB username
$password = "";         // or your DB password
$dbname = "web_project1";  // replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
