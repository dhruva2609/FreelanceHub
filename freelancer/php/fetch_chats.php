<?php
session_start();
require 'db_connect.php';

$current_user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Prepare the query to fetch distinct users who have communicated with the logged-in user
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
