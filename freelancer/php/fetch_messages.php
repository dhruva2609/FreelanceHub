<?php
require 'db_connect.php';

$sender = $_GET['sender_id'];  // Get sender ID
$receiver = $_GET['receiver_id'];  // Get receiver ID

// Query to fetch messages between the sender and receiver
$stmt = $conn->prepare("SELECT m.*, u.name as sender_name
  FROM messages m
  JOIN users u ON u.user_id = m.sender_id
  WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
  ORDER BY m.created_at ASC");

$stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);
$stmt->execute();

$result = $stmt->get_result();
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
