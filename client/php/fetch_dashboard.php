<?php
include 'db_connect.php'; // Include database connection

session_start();
$client_id = $_SESSION['client_id'] ?? 1; // Replace with actual session variable

$response = [
    "active_jobs" => 0,
    "proposals_received" => 0,
    "unread_messages" => 0,
    "recent_activity" => []
];

// Fetch Active Jobs
$query = "SELECT COUNT(*) AS active_jobs FROM jobs WHERE client_id = ? AND status = 'Active'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$stmt->bind_result($active_jobs);
$stmt->fetch();
$response["active_jobs"] = $active_jobs;
$stmt->close();

// Fetch Proposals Received
$query = "SELECT COUNT(*) AS proposals FROM proposals WHERE client_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$stmt->bind_result($proposals);
$stmt->fetch();
$response["proposals_received"] = $proposals;
$stmt->close();

// Fetch Unread Messages
$query = "SELECT COUNT(*) AS unread FROM messages WHERE receiver_id = ? AND is_read = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$stmt->bind_result($unread_messages);
$stmt->fetch();
$response["unread_messages"] = $unread_messages;
$stmt->close();

// Fetch Recent Activity
$query = "SELECT action_text FROM activity_log WHERE client_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $response["recent_activity"][] = $row['action_text'];
}
$stmt->close();

echo json_encode($response);
?>
