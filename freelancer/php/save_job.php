<?php
session_start();
header("Content-Type: application/json");


// Check if job_id is sent via POST
if (!isset($_POST['job_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing job ID']);
    exit;
}

require_once 'db_connect.php'; // Adjust path to your DB config if needed

$freelancer_id = $_SESSION['user_id'];
$job_id = intval($_POST['job_id']);

// Check if already saved
$checkStmt = $conn->prepare("SELECT * FROM saved_jobs WHERE freelancer_id = ? AND job_id = ?");
$checkStmt->bind_param("ii", $freelancer_id, $job_id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Job already saved']);
    exit;
}

// Insert saved job
$stmt = $conn->prepare("INSERT INTO saved_jobs (freelancer_id, job_id) VALUES (?, ?)");
$stmt->bind_param("ii", $freelancer_id, $job_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Job saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save job']);
}

$stmt->close();
$conn->close();
?>
