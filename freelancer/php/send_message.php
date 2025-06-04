<?php
require 'db_connect.php';

// Get POST data from frontend (sent message details)
$data = json_decode(file_get_contents('php://input'), true);
$sender = $data['sender_id'];
$receiver = $data['receiver_id'];
$message_text = $data['message_text'];
$job_id = $data['job_id'];  // Optional if you want to relate messages to a job

// Prepare the SQL query to insert the message
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, job_id, message_text) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $sender, $receiver, $job_id, $message_text);

// Execute the query and check for success
$success = $stmt->execute();

// Return response
echo json_encode(["success" => $success]);
?>
