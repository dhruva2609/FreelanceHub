<?php
// php/get_job_details.php
require_once 'db_connect.php';

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);

    $stmt = $conn->prepare("SELECT title, category, budget, description FROM jobs WHERE job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    if ($job) {
        echo json_encode(["success" => true, "job" => $job]);
    } else {
        echo json_encode(["success" => false, "message" => "Job not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No job_id provided."]);
}
?>
