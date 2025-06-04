<?php
session_start();
require_once 'db_connect.php'; // make sure this includes your DB connection

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$sender_id = $_SESSION['user_id'];
$receiver_id = $data['receiver_id'] ?? null;
$message_text = trim($data['message_text'] ?? '');

if (!$receiver_id || $message_text === '') {
  echo json_encode(['success' => false, 'error' => 'Invalid input']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message_text, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message_text);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'error' => $conn->error]);
}
$stmt->close();
$conn->close();
?>
