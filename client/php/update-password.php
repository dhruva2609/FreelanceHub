<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = $_POST['token'];

    // Verify token
    $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];

        // Update password & remove token
        $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL WHERE email=?");
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();

        echo "✅ Password updated! <a href='login.html'>Login</a>";
    } else {
        echo "❌ Invalid request.";
    }
}
$conn->close();
?>
