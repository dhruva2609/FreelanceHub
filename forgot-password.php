<?php
session_start();
include 'db_connect.php'; // Make sure this file exists and connects to the DB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($email) || empty($newPassword)) {
        echo "Both fields are required.";
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Check if the user exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No user found with this email.";
    } else {
        $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        if ($updateStmt->execute()) {
            echo "Password has been reset successfully.";
        } else {
            echo "Something went wrong. Try again.";
        }
    }
} else {
    echo "Invalid request.";
}
?>
