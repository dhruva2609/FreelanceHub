<?php
// php/delete_job.php

include 'db_connect.php'; // Adjust the path based on your folder structure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = intval($_POST['job_id']);

    if ($job_id > 0) {
        $stmt = $conn->prepare("DELETE FROM jobs WHERE job_id = ?");
        $stmt->bind_param("i", $job_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Job deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting job."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid job ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
