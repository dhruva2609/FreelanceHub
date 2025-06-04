<?php
session_start(); // ✅ Start the session to access $_SESSION

require 'db_connect.php'; // ✅ Database connection

// 📌 Validate if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$job_id = $_POST['job_id'];
$proposal_text = $_POST['cover_letter']; // 🔁 match DB column name
$bid_amount = $_POST['bid_amount'];
$delivery_time = $_POST['delivery_time'];
$time_unit = $_POST['time_unit'];
$freelancer_id = $_SESSION['user_id'];

$attachment = '';
if (!empty($_FILES['attachment']['name'])) {
    $targetDir = "../uploads/";
    $attachment = basename($_FILES["attachment"]["name"]);
    $targetFile = $targetDir . $attachment;
    move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFile);
}

// ✅ Insert into the correct columns
$stmt = $conn->prepare("INSERT INTO proposals (job_id, freelancer_id, proposal_text, bid_amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("iisd", $job_id, $freelancer_id, $proposal_text, $bid_amount);
$success = $stmt->execute();

echo json_encode([
    "success" => $success,
    "message" => $success ? "Proposal submitted successfully!" : "Failed to submit proposal: " . $stmt->error
]);
?>
