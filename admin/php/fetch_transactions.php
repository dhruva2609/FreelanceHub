<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Ensure database connection
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$sql = "SELECT payments.payment_id AS transaction_id, users.name AS user, 
               payments.amount, payments.status, 'Payment' AS type
        FROM payments
        JOIN job_users ON payments.job_user_id = job_users.job_user_id
        JOIN users ON job_users.user_id = users.user_id
        UNION
        SELECT escrow.escrow_id AS transaction_id, users.name AS user, 
               escrow.escrow_amount AS amount, escrow.status, 'Escrow' AS type
        FROM escrow
        JOIN job_users ON escrow.job_user_id = job_users.job_user_id
        JOIN users ON job_users.user_id = users.user_id
        ORDER BY transaction_id DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
    echo json_encode($transactions);
} else {
    echo json_encode(["error" => true, "message" => "No transactions found."]);
}

$conn->close();
?>
