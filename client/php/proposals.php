<?php
include 'php/db_connect.php';

$sql = "SELECT p.proposal_id, j.title AS job_title, u.name AS freelancer_name, p.proposal_text, p.bid_amount, p.status
        FROM proposals p
        JOIN jobs j ON p.job_id = j.job_id
        JOIN users u ON p.freelancer_id = u.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposals</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS -->
</head>
<body>
    <h2>Submitted Proposals</h2>
    <table border="1">
        <tr>
            <th>Job</th>
            <th>Freelancer</th>
            <th>Proposal</th>
            <th>Bid Amount</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['job_title']}</td>
                        <td>{$row['freelancer_name']}</td>
                        <td>{$row['proposal_text']}</td>
                        <td>\${$row['bid_amount']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No proposals submitted</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
