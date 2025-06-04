<?php
require 'db_connect.php'; // Ensure DB connection is included

header('Content-Type: application/json');

// Check if the database connection is successful
if (!$conn) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Fetch proposals with freelancer name and job title
$query = "
    SELECT p.proposal_id, 
           j.title AS job_title, 
           u.name AS freelancer, 
           p.proposal_text, 
           p.bid_amount, 
           p.status 
    FROM proposals p
    JOIN jobs j ON p.job_id = j.job_id
    JOIN users u ON p.freelancer_id = u.user_id
";

$result = $conn->query($query);

// Check for SQL errors
if (!$result) {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
    exit;
}

$proposals = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $proposals[] = $row;
    }
} else {
    echo json_encode(["message" => "No proposals found"]);
    exit;
}

echo json_encode($proposals);
$conn->close();
?>
