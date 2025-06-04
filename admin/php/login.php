<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin-dashboard.php");
            } elseif ($user['role'] == 'client') {
                header("Location: client-dashboard.php");
            } elseif ($user['role'] == 'freelancer') {
                header("Location: freelancer-dashboard.php");
            }
            exit();
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ No account found.";
    }
}
$conn->close();
?>
