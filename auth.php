<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "loggedIn" => false,
        "message" => "Not logged in"
    ]);
    exit();
}

echo json_encode([
    "loggedIn" => true,
    "user_id" => $_SESSION['user_id'],
    "user_name" => $_SESSION['user_name'] ?? "Unknown",
    "user_role" => $_SESSION['user_role'] ?? "guest"
]);
?>
