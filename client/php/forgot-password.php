<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token
        $token = bin2hex(random_bytes(50));
        
        // Store token
        $stmt = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Generate reset link (For local testing, display it)
        $reset_link = "http://localhost/reset-password.php?token=$token";
        echo "Reset your password: <a href='$reset_link'>$reset_link</a>";

        // In production, send this via email
    } else {
        echo "❌ Email not found.";
    }
}
$conn->close();
?>
