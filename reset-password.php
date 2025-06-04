<?php
session_start();
include 'db.php';

// Get token from URL
$token = $_GET['token'] ?? '';

if (!$token) {
    die("❌ Invalid token.");
}

// Check token
$stmt = $conn->prepare("SELECT email FROM users WHERE reset_token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['reset_email'] = $user['email']; // Store email for update
} else {
    die("❌ Invalid or expired link.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Reset Password</h2>
    <form action="update-password.php" method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <button type="submit">Update Password</button>
    </form>
</body>
</html>
