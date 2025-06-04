<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id, name, password, user_role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['user_role'];

            // Dashboard URL
            switch ($user['user_role']) {
                case 'admin':
                    $redirectUrl = '../webProject/admin/admin-dashboard.html';
                    break;
                case 'client':
                    $redirectUrl = '../webProject/client/client-dashboard.html';
                    break;
                case 'freelancer':
                    $redirectUrl = '../webProject/freelancer/freelancer-dashboard.html';
                    break;
                default:
                    $redirectUrl = 'index.html';
            }

            // Redirect via script (executed in new tab)
            echo "<script>
                window.location.href = '$redirectUrl';
              </script>";
            exit();
        } else {
            // Wrong password
            echo "<script>
              window.opener.document.getElementById('error-msg').innerText = '❌ Incorrect password.';
              window.close();
            </script>";
            exit();
        }
    } else {
        // No user found
        echo "<script>
          window.opener.document.getElementById('error-msg').innerText = '❌ No account found with this email.';
          window.close();
        </script>";
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
