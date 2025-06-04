<?php
include 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

// Prevent browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$sql = "SELECT e.escrow_id, j.title AS job_title, u1.name AS client, u2.name AS freelancer, e.escrow_amount, e.status
        FROM escrow e
        JOIN jobs j ON e.job_id = j.job_id
        JOIN users u1 ON e.client_id = u1.id
        JOIN users u2 ON e.freelancer_id = u2.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escrow Transactions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS -->
</head>
<body>
    <h2>Escrow Transactions</h2>
    <table border="1">
        <tr>
            <th>Job</th>
            <th>Client</th>
            <th>Freelancer</th>
            <th>Escrow Amount</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['job_title']}</td>
                        <td>{$row['client']}</td>
                        <td>{$row['freelancer']}</td>
                        <td>\${$row['escrow_amount']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No escrow transactions available</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
