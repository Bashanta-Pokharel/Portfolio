<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>alert("Please login first"); window.location.href="index.php";</script>';
    exit;
}

// Fetch total counts
$total_users = mysqli_num_rows(mysqli_query($con, "SELECT * FROM user"));

$total_approved_companies = mysqli_num_rows(
    mysqli_query($con, "SELECT * FROM company WHERE status = 'approved'")
);

$total_rejected_companies = mysqli_num_rows(
    mysqli_query($con, "SELECT * FROM company WHERE status = 'rejected'")
);

$total_pending_companies = mysqli_num_rows(
    mysqli_query($con, "SELECT * FROM company WHERE status = 'pending'")
);

$total_jobs = mysqli_num_rows(mysqli_query($con, "SELECT * FROM jobs"));
$total_accepted = mysqli_num_rows(mysqli_query($con, "SELECT * FROM accepted_application"));
$total_declined = mysqli_num_rows(mysqli_query($con, "SELECT * FROM declined_application"));
$total_pending = mysqli_num_rows(mysqli_query($con, "SELECT * FROM jobapplication"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JFS | Admin Dashboard</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>
        <div class="loginbuttons">
            <a href="logout.php" style="color: white; text-decoration: none;">
                <button>Logout</button>
            </a>
        </div>
    </div>

    <!-- Sidebar Navbar -->
    <div class="sidebar">
        <ul class="nav-links">
            <li><a href="adminhomepage.php" class="active">Home</a></li>
            <li><a href="adminviewcompany.php">View Companies</a></li>

            <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
            <li><a href="adminviewuser.php">View Users</a></li>
            <li><a href="adminviewjobs.php">View Jobs</a></li>
            <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
            <li><a href="allacceptedapplicant.php">All Accepted Applicants</a></li>
            <li><a href="allrejectedapplicant.php">All Rejected Applicants</a></li>
            <li><a href="addcategory.php">Add Category</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <div class="welcome">
                <p>Welcome, <span><?php echo $_SESSION["username"]; ?></span>. You are logged in to the Job Finding
                    System.</p>
            </div>
            <h1>Dashboard Overview</h1>

            <div class="jobs" style="display:flex; flex-wrap:wrap; gap:20px;">
                <!-- Total Users -->
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">TOTAL USERS</h3>
                    <p class="card-count"><?php echo $total_users; ?></p>
                </div>

                <!-- Total Companies -->
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">TOTAL APPROVED COMPANIES</h3>
                    <p class="card-count"><?php echo $total_approved_companies; ?></p>
                </div>
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">TOTAL DECLINED COMPANIES</h3>
                    <p class="card-count"><?php echo $total_rejected_companies; ?></p>
                </div>
                <div class="job-card notify-red">
                    <h3 class="card-title">TOTAL PENDING COMPANIES</h3>
                    <p class="card-count"><?php echo $total_pending_companies; ?></p>
                </div>


                <!-- Total Jobs -->
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">TOTAL JOBS</h3>
                    <p class="card-count"><?php echo $total_jobs; ?></p>
                </div>

                <!-- Total Accepted Applications -->
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">ACCEPTED APPLICATIONS</h3>
                    <p class="card-count"><?php echo $total_accepted; ?></p>
                </div>

                <!-- Total Declined Applications -->
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">DECLINED APPLICATIONS</h3>
                    <p class="card-count"><?php echo $total_declined; ?></p>
                </div>

                <!-- Total Pending Applications -->
                <div class="job-card" style="text-align:center;">
                    <h3 class="card-title">PENDING APPLICATIONS</h3>
                    <p class="card-count"><?php echo $total_pending; ?></p>
                </div>
            </div>

        </div>
    </div>
</body>

</html>