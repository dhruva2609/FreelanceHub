<?php 
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_role = $_POST['role']; // freelancer, client, admin

    // Validate empty fields
    if (empty($name) || empty($email) || empty($_POST['password']) || empty($user_role)) {
        echo "❌ All fields are required.";
        exit();
    }

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $user_role);

    if ($stmt->execute()) {
        echo "✅ Account created! <a href='login.html'>Login here</a>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
