<?php
// Include the database connection
include('db_connect.php');

// Initialize variables
$job = null;
$error_message = '';

// Check if the job_id is passed via GET request
if (isset($_GET['job_id']) && is_numeric($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch job details from the database
    $query = "SELECT * FROM jobs WHERE job_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $job_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $job = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Job not found.";
    }

    mysqli_stmt_close($stmt);
} else {
    $error_message = "Invalid job ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/commonf.css">
    <script defer src="js/commonf.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F4F6F9;
            padding-top: 70px;
        }

        .container {
            margin-top: 20px;
        }

        .job-details-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .job-details-container h3 {
            font-size: 28px;
            color: #1e3c72;
            margin-bottom: 15px;
        }

        .job-details-container p {
            font-size: 16px;
            color: #555;
            margin: 8px 0;
        }

        .error-message {
            font-size: 18px;
            color: #dc3545;
            text-align: center;
        }

        @media (max-width: 768px) {
            .job-details-container {
                padding: 15px;
            }

            .job-details-container h3 {
                font-size: 24px;
            }

            .job-details-container p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="../freelancer-dashboard.html">Freelancer Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="../freelancer-dashboard.html">Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="findWorkDropdown" role="button" data-bs-toggle="dropdown">
                        Find Work
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../freelancer-jobs.html">Browse Jobs</a></li>
                        <li><a class="dropdown-item" href="../saved-jobs.html">Saved Jobs</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="../freelancer-proposals.html">Proposals</a></li>
                <li class="nav-item"><a class="nav-link" href="../freelancer-messages.html">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="../freelancer-progress.html">Progress</a></li>
                <li class="nav-item"><a class="nav-link" href="../freelancer-earnings.html">Earnings</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container">
    <div class="job-details-container">
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php elseif ($job): ?>
            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            <p><strong>Budget:</strong> $<?php echo number_format($job['budget'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($job['status']); ?></p>
            <p><strong>Posted on:</strong> <?php echo date("F j, Y, g:i a", strtotime($job['created_at'])); ?></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
