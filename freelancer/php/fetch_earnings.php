<?php
session_start();
include '../../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    echo json_encode(["error" => "Unauthorized access."]);
    exit();
}

$freelancer_id = $_SESSION['user_id'];

// Fetch total earnings, pending payments, and available balance
$query = "
    SELECT 
        COALESCE(SUM(CASE WHEN p.status = 'released' THEN p.amount ELSE 0 END), 0) AS total_earnings,
        COALESCE(SUM(CASE WHEN p.status = 'pending' THEN p.amount ELSE 0 END), 0) AS pending_payments,
        COALESCE(SUM(CASE WHEN p.status = 'released' THEN p.amount ELSE 0 END) - 
                 SUM(CASE WHEN p.status = 'disputed' THEN p.amount ELSE 0 END), 0) AS available_balance
    FROM payments p
    JOIN job_users ju ON p.job_user_id = ju.job_user_id
    WHERE ju.user_id = ? AND ju.role = 'freelancer'
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Fetch transactions list with client name
$transactions_query = "
    SELECT 
        p.transaction_date, 
        u.name AS client_name, 
        p.amount, 
        p.status
    FROM payments p
    JOIN job_users ju ON p.job_user_id = ju.job_user_id
    JOIN jobs j ON ju.job_id = j.job_id  -- Get job details
    JOIN users u ON j.client_id = u.user_id  -- Fetch client name from users table
    WHERE ju.user_id = ? AND ju.role = 'freelancer'
    ORDER BY p.transaction_date DESC
";

$stmt2 = $conn->prepare($transactions_query);
if (!$stmt2) {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
    exit();
}

$stmt2->bind_param("i", $freelancer_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$transactions = [];

while ($row = $result2->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt2->close();
$conn->close();

echo json_encode([
    "total_earnings" => $data['total_earnings'] ?? "0.00",
    "pending_payments" => $data['pending_payments'] ?? "0.00",
    "available_balance" => $data['available_balance'] ?? "0.00",
    "transactions" => $transactions
]);
?>
