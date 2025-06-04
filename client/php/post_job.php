<?php

// INCLUDE FILES
require_once '../../db_connect.php';
require_once '../../auth.php';



// DB CONNECTION
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}

// RETRIEVE POST DATA
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = trim($_POST['category'] ?? '');
$budget      = floatval($_POST['budget'] ?? 0);
$client_id   = $_SESSION['user_id'];

// VALIDATE INPUT
if (!$title || !$description || !$category || $budget <= 0) {
    http_response_code(400);
    echo "Please fill in all fields.";
    exit;
}

// ✅ FIXED: Removed extra ? for created_at
$stmt = $conn->prepare("INSERT INTO jobs (client_id, title, category, description, budget, created_at) VALUES (?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    http_response_code(500);
    echo "SQL Prepare failed: " . $conn->error;
    exit;
}

// ✅ FIXED: Removed 6th parameter (only 5 placeholders)
$stmt->bind_param("isssd", $client_id, $title, $category, $description, $budget);

// EXECUTE AND RESPOND
if ($stmt->execute()) {
    echo "Job posted successfully!";
} else {
    http_response_code(500);
    echo "Failed to post job: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
