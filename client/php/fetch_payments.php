<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ CHECK DATABASE CONNECTION
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// ✅ GET USER ROLE (client or freelancer)
$sql_role = "SELECT user_role FROM users WHERE user_id = ?";
$stmt_role = $conn->prepare($sql_role);

if (!$stmt_role) {
    echo json_encode(["error" => "SQL Error (Role Query): " . $conn->error]);
    exit;
}

$stmt_role->bind_param("i", $user_id);
$stmt_role->execute();
$result_role = $stmt_role->get_result();
$user_data = $result_role->fetch_assoc();
$stmt_role->close();

if (!$user_data) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$role = $user_data['user_role']; // Corrected column name
$transactions = [];

// ✅ GET TRANSACTION HISTORY BASED ON USER ROLE
if ($role === 'client') {
    $sql_transactions = "SELECT p.transaction_date, u.name AS freelancer, p.amount, p.status
                         FROM payments p
                         JOIN job_users ju ON p.job_user_id = ju.job_user_id
                         JOIN users u ON ju.user_id = u.user_id
                         WHERE ju.job_id IN (SELECT job_id FROM jobs WHERE client_id = ?)
                         ORDER BY p.transaction_date DESC";
} else { // Freelancer transactions
    $sql_transactions = "SELECT p.transaction_date, u.name AS client, p.amount, p.status
                         FROM payments p
                         JOIN job_users ju ON p.job_user_id = ju.job_user_id
                         JOIN jobs j ON ju.job_id = j.job_id
                         JOIN users u ON j.client_id = u.user_id
                         WHERE ju.user_id = ?
                         ORDER BY p.transaction_date DESC";
}

$stmt_transactions = $conn->prepare($sql_transactions);

if (!$stmt_transactions) {
    echo json_encode(["error" => "SQL Error (Transactions Query): " . $conn->error]);
    exit;
}

$stmt_transactions->bind_param("i", $user_id);
$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();

while ($row = $result_transactions->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt_transactions->close();
$conn->close();

echo json_encode(["role" => $role, "transactions" => $transactions]);
?>
