<?php
require_once 'db_connect.php';

// Validate required POST data
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : null;
$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : null;

if (!$receiver_id || !$sender_id) {
    echo json_encode(["error" => "Missing receiver_id or sender_id"]);
    exit;
}

// Update status to 'read'
$sql = "UPDATE messages SET status = 'read' WHERE sender_id = ? AND receiver_id = ? AND status != 'read'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $sender_id, $receiver_id);
$stmt->execute();

echo json_encode(["status" => "success"]);
?>
