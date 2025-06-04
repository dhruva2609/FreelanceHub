<?php
session_start();
include 'db_connect.php'; // Ensure database connection

header('Content-Type: application/json'); // Set response type to JSON

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized: User not logged in']);
    exit;
}

// Ensure only freelancers can access this
if ($_SESSION['user_role'] !== 'freelancer') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized: Access denied']);
    exit;
}

$freelancer_id = $_SESSION['user_id']; // Get freelancer's ID

// Ensure database connection exists
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Database connection error']);
    exit;
}

// Query to fetch freelancer proposals
$query = "SELECT p.proposal_id, p.proposal_text, p.bid_amount, p.status, p.created_at, 
                 j.title AS job_title, j.job_id
          FROM proposals p
          JOIN jobs j ON p.job_id = j.job_id
          WHERE p.freelancer_id = ?
          ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database query error']);
    exit;
}

$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();

$proposals = [];

while ($row = $result->fetch_assoc()) {
    $proposals[] = [
        'proposal_id' => $row['proposal_id'],
        'job_id' => $row['job_id'],
        'job_title' => $row['job_title'],
        'proposal_text' => substr($row['proposal_text'], 0, 100) . '...', // Shorten text
        'bid_amount' => $row['bid_amount'],
        'status' => $row['status'],
        'created_at' => $row['created_at'],
    ];
}

// Close statement and connection
$stmt->close();
$conn->close();

// Return success response
echo json_encode(['success' => true, 'proposals' => $proposals]);
?>
