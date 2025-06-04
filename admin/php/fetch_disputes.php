<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// Check if admin is logged in (optional but recommended)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$sql = "SELECT 
            d.dispute_id,
            j.title AS job_title,
            d.dispute_reason,
            d.status,
            d.created_at,
            u1.name AS raised_by,
            u2.name AS against
        FROM disputes d
        JOIN jobs j ON d.job_id = j.job_id
        JOIN users u1 ON d.raised_by = u1.user_id
        JOIN users u2 ON j.client_id = u2.user_id
        ORDER BY d.created_at DESC";

$result = mysqli_query($conn, $sql);

if ($result) {
    $disputes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $disputes[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $disputes]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database query failed.']);
}
?>
