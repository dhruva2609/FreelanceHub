<?php
session_start();
require 'db_connect.php'; // Connect to MySQL

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

$freelancer_id = $_SESSION['user_id'];

$sql = "
SELECT m.*, j.title AS job_title 
FROM milestones m
JOIN jobs j ON m.job_id = j.job_id
WHERE m.freelancer_id = ?
ORDER BY m.due_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();

$milestones = [];
while ($row = $result->fetch_assoc()) {
    $milestones[] = $row;
}

echo json_encode($milestones);
?>
