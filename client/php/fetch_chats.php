<?php
session_start();
require 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

// Prevent browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$current_user_id = 1; // For testing, normally use: $_SESSION['user_id']

// Prepare the query to fetch the sender's id and name
$stmt = $conn->prepare("
    SELECT DISTINCT u.user_id, u.name 
    FROM messages m
    JOIN users u ON u.user_id = m.sender_id
    WHERE m.receiver_id = ? OR m.sender_id = ?
");

$stmt->bind_param("ii", $current_user_id, $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare the response
$users = [];
while($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Return the users as JSON response
echo json_encode($users);
?>
