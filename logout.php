<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Logging Out...</title>
  <script>
    // Try to close the tab
    window.close();

    // If window not closed in 1 second, redirect to login page
    setTimeout(() => {
      window.location.href = '../webProject/login.html';
    }, 1000);
  </script>
</head>
<body>
  <p>Logging you out... Please wait.</p>
</body>
</html>
