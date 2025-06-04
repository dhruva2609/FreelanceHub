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
// Get sender ID from session (client)
$sender = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Get receiver ID from GET parameter (freelancer)
$receiver = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;

if ($sender === 0 || $receiver === 0) {
    echo json_encode(["error" => "Invalid sender or receiver ID"]);
    exit;
}

// Prepare and execute the query
$stmt = $conn->prepare("
    SELECT m.*, u.name AS sender_name
    FROM messages m
    JOIN users u ON u.user_id = m.sender_id
    WHERE (m.sender_id = ? AND m.receiver_id = ?) 
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.created_at ASC
");

$stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
