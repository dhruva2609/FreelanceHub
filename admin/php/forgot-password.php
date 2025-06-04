<?php
include 'db_connect.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password for the given email
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        echo "Password updated successfully. You can now <a href='login.html'>login</a> with your new password.";
    } else {
        echo "Error updating password.";
    }
    
    $stmt->close();
}
$conn->close();
?>
