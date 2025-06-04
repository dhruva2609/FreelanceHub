<?php
session_start();
require 'db_connect.php'; // Database connection
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_SESSION['user_id'])) {
    // Optional: prevent caching too
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    header("Location:../login.html");
    exit();
}
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}
// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) as total FROM users";
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total'];

// Fetch active jobs
$activeJobsQuery = "SELECT COUNT(*) as total FROM jobs WHERE status='open'";
$activeJobsResult = mysqli_query($conn, $activeJobsQuery);
$activeJobs = mysqli_fetch_assoc($activeJobsResult)['total'];

// Fetch pending withdrawals
$pendingWithdrawalsQuery = "SELECT COUNT(*) as total FROM payments WHERE status='pending'";
$pendingWithdrawalsResult = mysqli_query($conn, $pendingWithdrawalsQuery);
$pendingWithdrawals = mysqli_fetch_assoc($pendingWithdrawalsResult)['total'];

// Fetch open disputes
$openDisputesQuery = "SELECT COUNT(*) as total FROM disputes WHERE status='open'";
$openDisputesResult = mysqli_query($conn, $openDisputesQuery);
$openDisputes = mysqli_fetch_assoc($openDisputesResult)['total'];

// Fetch recent activity (latest 5 actions)
$recentActivityQuery = "SELECT users.name, notifications.content, notifications.created_at 
                        FROM notifications 
                        JOIN users ON notifications.user_id = users.user_id 
                        ORDER BY notifications.created_at DESC LIMIT 5";
$recentActivityResult = mysqli_query($conn, $recentActivityQuery);
$recentActivity = mysqli_fetch_all($recentActivityResult, MYSQLI_ASSOC);

// Return data as JSON
echo json_encode([
    'total_users' => $totalUsers,
    'active_jobs' => $activeJobs,
    'pending_withdrawals' => $pendingWithdrawals,
    'open_disputes' => $openDisputes,
    'recent_activity' => $recentActivity
]);
?>
