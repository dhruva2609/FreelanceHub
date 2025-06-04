<?php
include 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "SELECT j.job_id, j.title, j.category, j.budget, j.status, u.name AS client_name 
        FROM jobs j 
        JOIN users u ON j.client_id = u.user_id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['category']}</td>
                <td>{$row['client_name']}</td>
                <td>\${$row['budget']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No jobs available</td></tr>";
}

$conn->close();
?>
