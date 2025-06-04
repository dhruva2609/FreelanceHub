<?php
require_once 'db_connect.php'; // Include database connection
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$sql = "SELECT user_id, name, email, user_role, status FROM users";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "SQL Error", "message" => $conn->error]);
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();
?>
