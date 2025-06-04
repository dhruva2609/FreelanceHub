<?php
header('Content-Type: application/json');
$host = "localhost";
$db = "web_project1";
$user = "root";
$pass = "";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

// Get filters from query string
$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Build base SQL query
$sql = "SELECT jobs.job_id, jobs.title, jobs.category, jobs.budget, jobs.description, users.name AS client_name
        FROM jobs 
        JOIN users ON jobs.client_id = users.user_id 
        WHERE jobs.status = 'open' AND users.user_role = 'client'";

// Apply search filter
if (!empty($query)) {
    $sql .= " AND (jobs.title LIKE '%$query%' OR jobs.description LIKE '%$query%')";
}

// Apply category filter
if (!empty($category)) {
    $sql .= " AND jobs.category = '$category'";
}

// Run query
$result = $conn->query($sql);
$jobs = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

echo json_encode($jobs);
$conn->close();
?>
