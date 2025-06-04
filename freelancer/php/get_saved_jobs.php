<?php
session_start();
header('Content-Type: application/json');

// Ensure session user is available
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once 'db_connect.php'; // Adjust path if needed

$freelancer_id = $_SESSION['user_id'];

// Fetch saved jobs for this freelancer
$query = "
    SELECT j.job_id, j.title, j.category, j.budget, j.description, u.name AS client_name
    FROM saved_jobs s
    JOIN jobs j ON s.job_id = j.job_id
    JOIN users u ON j.client_id = u.user_id
    WHERE s.freelancer_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();

$savedJobs = [];

while ($row = $result->fetch_assoc()) {
    $savedJobs[] = $row;
}

echo json_encode($savedJobs);

$stmt->close();
$conn->close();
?>
