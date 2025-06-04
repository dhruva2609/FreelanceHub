<?php
session_start();
require 'db_connect.php'; // Connect to MySQL

// Check if the user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

$freelancer_id = $_SESSION['user_id'];

// Fetch current earnings
$earnings_sql = "
    SELECT SUM(amount) AS total_earnings 
    FROM payments p
    JOIN job_users ju ON p.job_user_id = ju.job_user_id
    WHERE ju.user_id = ? AND p.status = 'released'
";
$earnings_stmt = $conn->prepare($earnings_sql);
if ($earnings_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}
$earnings_stmt->bind_param("i", $freelancer_id);
$earnings_stmt->execute();
$earnings_result = $earnings_stmt->get_result();
$earnings = $earnings_result->fetch_assoc();
$current_earnings = $earnings['total_earnings'] ? $earnings['total_earnings'] : 0.00;

// Fetch ongoing projects count
$ongoing_sql = "
    SELECT COUNT(*) AS ongoing_count 
    FROM milestones m 
    JOIN jobs j ON m.job_id = j.job_id 
    WHERE m.freelancer_id = ? AND m.status IN ('pending', 'in_progress')
";
$ongoing_stmt = $conn->prepare($ongoing_sql);
if ($ongoing_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}
$ongoing_stmt->bind_param("i", $freelancer_id);
$ongoing_stmt->execute();
$ongoing_result = $ongoing_stmt->get_result();
$ongoing = $ongoing_result->fetch_assoc();
$ongoing_projects = $ongoing['ongoing_count'] ? $ongoing['ongoing_count'] : 0;

// Fetch new messages count
$messages_sql = "
    SELECT COUNT(*) AS new_messages_count 
    FROM messages 
    WHERE receiver_id = ? AND status = 'unread'
";
$messages_stmt = $conn->prepare($messages_sql);
if ($messages_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}
$messages_stmt->bind_param("i", $freelancer_id);
$messages_stmt->execute();
$messages_result = $messages_stmt->get_result();
$messages = $messages_result->fetch_assoc();
$new_messages = $messages['new_messages_count'] ? $messages['new_messages_count'] : 0;

// Fetch completed projects count
$completed_sql = "
    SELECT COUNT(*) AS completed_count 
    FROM milestones m 
    JOIN jobs j ON m.job_id = j.job_id 
    WHERE m.freelancer_id = ? AND m.status = 'completed'
";
$completed_stmt = $conn->prepare($completed_sql);
if ($completed_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}
$completed_stmt->bind_param("i", $freelancer_id);
$completed_stmt->execute();
$completed_result = $completed_stmt->get_result();
$completed = $completed_result->fetch_assoc();
$completed_projects = $completed['completed_count'] ? $completed['completed_count'] : 0;

// Return data in a structured array
$response = [
    'earnings' => number_format($current_earnings, 2), // Formatting earnings to 2 decimal places
    'ongoing' => $ongoing_projects,
    'messages' => $new_messages,
    'completed' => $completed_projects,
];

// Send response as JSON
echo json_encode($response);
?>
