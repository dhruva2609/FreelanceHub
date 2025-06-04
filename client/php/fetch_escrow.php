<?php
include 'db_connect.php';

$sql = "SELECT e.escrow_id, j.title AS job_title, u1.name AS client, u2.name AS freelancer, e.escrow_amount, e.status
        FROM escrow e
        JOIN jobs j ON e.job_id = j.job_id
        JOIN users u1 ON e.client_id = u1.id
        JOIN users u2 ON e.freelancer_id = u2.id";

$result = $conn->query($sql);

$escrow = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $escrow[] = $row;
    }
}
echo json_encode($escrow);
?>
