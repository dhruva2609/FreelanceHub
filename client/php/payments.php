<?php
include 'php/db_connect.php';

$sql = "SELECT p.payment_id, j.title AS job_title, u1.name AS client, u2.name AS freelancer, p.amount, p.status
        FROM payments p
        JOIN jobs j ON p.job_id = j.job_id
        JOIN users u1 ON p.client_id = u1.id
        JOIN users u2 ON p.freelancer_id = u2.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS -->
</head>
<body>
    <h2>Completed Payments</h2>
    <table border="1">
        <tr>
            <th>Job</th>
            <th>Client</th>
            <th>Freelancer</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['job_title']}</td>
                        <td>{$row['client']}</td>
                        <td>{$row['freelancer']}</td>
                        <td>\${$row['amount']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No payments made</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
