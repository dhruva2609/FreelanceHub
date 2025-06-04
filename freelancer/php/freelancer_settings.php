<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    header("Location: ../login.html");
    exit();
}

$freelancer_id = $_SESSION['user_id'];
$user_details = [];

// Fetch freelancer details
$query = "SELECT name, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $freelancer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_details = $result->fetch_assoc();
    $stmt->close();
}

// Fetch email notification preference
$notifications_enabled = "enabled"; // Default
$pref_query = "SELECT is_enabled FROM client_preferences WHERE client_id = ? AND preference_type = 'message_notifications'";
$pref_stmt = $conn->prepare($pref_query);
if ($pref_stmt) {
    $pref_stmt->bind_param("i", $freelancer_id);
    $pref_stmt->execute();
    $pref_result = $pref_stmt->get_result();
    if ($pref_row = $pref_result->fetch_assoc()) {
        $notifications_enabled = $pref_row['is_enabled'] ? "enabled" : "disabled";
    }
    $pref_stmt->close();
}

$conn->close();

// Return data as JSON for AJAX
echo json_encode([
    'name' => htmlspecialchars($user_details['name']),
    'email' => htmlspecialchars($user_details['email']),
    'notifications' => $notifications_enabled
]);
?>
