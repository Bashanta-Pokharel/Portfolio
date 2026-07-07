<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>alert("Please login first"); window.location.href="index.php";</script>';
    exit;
}

// Get company ID from URL
if (!isset($_GET['cid'])) {
    echo '<script>alert("Invalid Company ID"); window.location.href="adminviewcompany.php";</script>';
    exit;
}

$cid = $_GET['cid'];

// Fetch company info
$company_query = mysqli_query($con, "SELECT * FROM company WHERE cid='$cid'");
if (!$company_query || mysqli_num_rows($company_query) == 0) {
    echo '<script>alert("Company not found"); window.location.href="adminviewcompany.php";</script>';
    exit;
}

$company = mysqli_fetch_assoc($company_query);
$company_name = $company['company_name'];

// Fetch jobs of this company
$job_query = mysqli_query($con, "SELECT * FROM jobs WHERE company_id='$cid' ORDER BY id DESC");
$total_jobs = mysqli_num_rows($job_query); // Total jobs
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jobs of <?php echo $company_name; ?></title>
<link rel="stylesheet" href="style3.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>
    <div class="loginbuttons">
        <a href="logout.php"><button>Logout</button></a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="adminhomepage.php">Home</a></li>
        <li><a href="adminviewcompany.php">View Companies</a></li>
        <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
        <li><a href="adminviewuser.php">View Users</a></li>
        <li><a href="adminviewjobs.php">View Jobs</a></li>
        <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
        <li><a href="allacceptedapplicant.php">All Accepted Applicants</a></li>
        <li><a href="allrejectedapplicant.php">All Rejected Applicants</a></li>
        <li><a href="addcategory.php">Add Category</a></li>
    </ul>

    <!-- Show only current company -->
    <h4 style="margin-left:15px; margin-top:20px;">Company</h4>
    <ul class="nav-links">
        <li><a href="adminviewcompanyjobs.php?cid=<?php echo $cid; ?>" class="active"><?php echo $company_name; ?></a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content">
        <h2>Jobs Posted by <?php echo $company_name; ?></h2>
        <p style="font-weight:bold; margin-bottom:15px;">Total Jobs: <?php echo $total_jobs; ?></p>

        <div class="jobs">
        <?php
        if ($total_jobs > 0) {
            while ($job = mysqli_fetch_assoc($job_query)) {
                echo "
                <div class='job-card'>
                    <img src='images/{$job['image']}' alt='{$job['title']}'>
                    <h3>{$job['title']}</h3>
                    <p><strong>Description:</strong> {$job['description']}</p>
                    <p><strong>Location:</strong> {$job['location']}</p>
                    <p><strong>Qualification:</strong> {$job['qualification']}</p>
                    <p><strong>Salary:</strong> {$job['salary']}</p>
                    <p><strong>Category:</strong> {$job['category']}</p>
                    <p><strong>Posted On:</strong> ".date("d-m-Y", strtotime($job['openeddate']))."</p>
                    <p><strong>Expiry Date:</strong> ".date("d-m-Y", strtotime($job['expirydate']))."</p>

                    <div style='margin-top:12px;'>
                        <button class='btn' style='display:block; margin-bottom:6px;' onclick=\"window.location.href='admineditjobs.php?job_id={$job['id']}'\">Edit</button>
                        <button class='btn' style='display:block;' onclick=\"if(confirm('Delete this job?')) window.location.href='admindeletejobs.php?job_id={$job['id']}';\">Delete</button>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p style='font-size:1.5rem;text-align:center;'>No jobs posted by this company yet.</p>";
        }
        ?>
        </div>
    </div>
</div>

</body>
</html>
