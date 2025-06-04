<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id, name, password, user_role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['user_role'];

            // ✅ Redirect to the appropriate HTML page
            if ($user['user_role'] === 'admin') {
                header("Location: ../admin/admin-dashboard.html");
            } elseif ($user['user_role'] === 'client') {
                header("Location: ../client/client-dashboard.html");
            } elseif ($user['user_role'] === 'freelancer') {
                header("Location: ../freelancer/freelancer-dashboard.html");
            }
            exit();
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ No account found.";
    }

    $stmt->close();
}
$conn->close();
?>
