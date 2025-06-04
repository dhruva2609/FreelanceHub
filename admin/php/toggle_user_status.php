<?php
require_once 'db_connect.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_POST['user_id']) || !isset($_POST['status'])) {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

$user_id = $_POST['user_id'];
$status = $_POST['status'];

$sql = "UPDATE users SET status = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "User status updated to $status"]);
} else {
    echo json_encode(["error" => "Failed to update status"]);
}

$stmt->close();
$conn->close();
?>
