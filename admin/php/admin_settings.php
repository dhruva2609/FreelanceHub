<?php
session_start();
header("Content-Type: application/json");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// Debugging: Check if session is set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

require_once "db_connect.php"; // Ensure this file contains proper DB connection

$admin_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Fetch Admin Details
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "Admin not found"]);
    }
    $stmt->close();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($name) || empty($email)) {
        echo json_encode(["error" => "Name and Email are required"]);
        exit;
    }

    // Update Name & Email
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $name, $email, $admin_id);
    $stmt->execute();
    $stmt->close();

    // Update Password if provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $hashed_password, $admin_id);
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode(["success" => "Admin settings updated successfully"]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Invalid request method"]);
?>
