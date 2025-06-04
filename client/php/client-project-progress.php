<?php
session_start();
require 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$client_id = $_SESSION['user_id'];

$query = "
    SELECT j.job_id, j.title, m.*
    FROM jobs j
    JOIN milestones m ON j.job_id = m.job_id
    WHERE j.client_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$result = $stmt->get_result();

$milestones = [];
$total = 0;
$completed = 0;

while ($row = $result->fetch_assoc()) {
    $milestones[] = $row;
    $total++;
    if ($row['status'] === 'completed') {
        $completed++;
    }
}

$completion_percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

echo json_encode([
    'milestones' => $milestones,
    'completion_percentage' => $completion_percentage
]);

$stmt->close();
$conn->close();
?>
