<?php
header("Content-Type: application/json");
include "db_connect.php"; // Ensure this file contains the correct DB connection
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$sql = "SELECT jobs.job_id, jobs.title, users.name AS client, jobs.status 
        FROM jobs 
        JOIN users ON jobs.client_id = users.user_id";
$result = $conn->query($sql);

$jobs = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = [
            "job_id" => $row["job_id"],
            "title" => $row["title"],
            "client" => $row["client"],
            "status" => $row["status"]
        ];
    }
}

echo json_encode($jobs);
$conn->close();
?>
