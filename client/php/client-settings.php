<?php
// php/client-settings.php
session_start();
require_once 'db_connect.php'; // Database connection

$response = ['status' => 'error', 'message' => 'Unknown error'];

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    $response['message'] = 'Unauthorized';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        $response['message'] = 'User not found';
        echo json_encode($response);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? null;
    $notifications = $_POST['notifications'] ?? 'enabled';

    // Validate inputs
    if (empty($name) || empty($email)) {
        $response['message'] = 'Name and Email are required';
        echo json_encode($response);
        exit();
    }

    // Update user info
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        // Save email notifications preference (enabled/disabled)
        $notif_value = ($notifications === 'enabled') ? 1 : 0;

        // Delete existing preference
        $conn->query("DELETE FROM client_preferences WHERE client_id = $user_id AND preference_type = 'message_notifications'");

        // Insert new preference
        $prefStmt = $conn->prepare("INSERT INTO client_preferences (client_id, preference_type, is_enabled) VALUES (?, 'message_notifications', ?)");
        $prefStmt->bind_param("ii", $user_id, $notif_value);
        $prefStmt->execute();

        $response = ['status' => 'success', 'message' => 'Settings updated successfully'];
    } else {
        $response['message'] = 'Error updating settings';
    }

    echo json_encode($response);
    exit();
}

?>
