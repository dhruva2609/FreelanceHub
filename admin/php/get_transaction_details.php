<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    echo json_encode(["error" => true, "message" => "Invalid transaction ID."]);
    exit;
}

// Try payments first
$sql = "SELECT payments.payment_id AS transaction_id, users.name AS user,
               payments.amount, payments.status, 'Payment' AS type
        FROM payments
        JOIN job_users ON payments.job_user_id = job_users.job_user_id
        JOIN users ON job_users.user_id = users.user_id
        WHERE payments.payment_id = $id";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    // Try escrow if payment not found
    $sql = "SELECT escrow.escrow_id AS transaction_id, users.name AS user,
                   escrow.escrow_amount AS amount, escrow.status, 'Escrow' AS type
            FROM escrow
            JOIN job_users ON escrow.job_user_id = job_users.job_user_id
            JOIN users ON job_users.user_id = users.user_id
            WHERE escrow.escrow_id = $id";

    $result = $conn->query($sql);
}

if ($result && $result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => true, "message" => "Transaction not found."]);
}

$conn->close();
