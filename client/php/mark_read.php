<?php
session_start();
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_POST["sender_id"];
    $receiver_id = $_SESSION["user_id"];

    $query = "UPDATE messages SET status='read' WHERE sender_id=? AND receiver_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();

    echo json_encode(["status" => "success"]);
}
?>
