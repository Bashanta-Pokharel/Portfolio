<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; // DB connection

    // Total accepted applications
    $total_accepted = mysqli_num_rows(mysqli_query($con, "SELECT * FROM accepted_application"));

    // Fetch all accepted applications
    $sql = "SELECT * FROM accepted_application ORDER BY id DESC";
    $result = mysqli_query($con, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>JFS - Accepted Applications</title>
        <link rel="stylesheet" href="style3.css">
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding System</div>
            <div style="text-align:center; font-size: 1.8em;">Admin Panel</div>
            <div class="loginbuttons">
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <button>Logout</button>
                </a>
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
                <li><a href="adminviewpendingrequest.php">All pending Applications</a></li>
                <li><a href="allacceptedapplicant.php" class="active">Accepted Applications</a></li>
                <li><a href="allrejectedapplicant.php">All Rejected Applicants</a></li>
                <li><a href="addcategory.php">Add Category</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content">
                <h2>All Accepted Applications </h2>

                <!-- Total Accepted Card -->
                <h3 style="font-weight:bold; margin-bottom:15px;">Total Accepted Applications:
                    <?php echo $total_accepted; ?>
                </h3>





                <div class="jobs" style="display:flex; flex-wrap:wrap; gap:20px;">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $job_id = $row['job_id'];
                            $company_name = "Unknown";
                            $job_title = "Unknown";

                            // Fetch job info
                            $job_query = "SELECT title, company_id FROM jobs WHERE id = $job_id";
                            $job_result = mysqli_query($con, $job_query);
                            if ($job_result && mysqli_num_rows($job_result) > 0) {
                                $job_data = mysqli_fetch_assoc($job_result);
                                $job_title = $job_data['title'];
                                $company_id = $job_data['company_id'];
                                $photo = $row['photo'];
                                $photoTag = (!empty($photo) && file_exists("photos/" . $photo))
                                    ? "<img src='photos/{$photo}' alt='{$row['fullname']}' width='150' style='border-radius:8px; margin-bottom:10px;'>"
                                    : "<span>No Photo</span>";


                                // Fetch company name
                                $company_query = "SELECT company_name FROM company WHERE cid = $company_id";
                                $company_result = mysqli_query($con, $company_query);
                                if ($company_result && mysqli_num_rows($company_result) > 0) {
                                    $company_data = mysqli_fetch_assoc($company_result);
                                    $company_name = $company_data['company_name'];
                                }
                            }

                            echo "
<div class='job-card'>
    $photoTag
    <h3>{$row['fullname']}</h3>
    <p><strong>Company:</strong> {$company_name}</p>
    <p><strong>Job Title:</strong> {$job_title}</p>
    <p><strong>Email:</strong> {$row['email']}</p>
    <p><strong>Phone:</strong> {$row['phone']}</p>
    <p><strong>Address:</strong> {$row['address']}</p>
    <p><strong>Skills:</strong> {$row['skills']}</p>
    <p><strong>Experience:</strong> {$row['experiences']}</p>
    <p><strong>Accepted Date:</strong> " . date("d-m-Y", strtotime($row['accepted_at'])) . "</p>
    <p><strong>CV:</strong> <a href='cvs/{$row['cv']}' target='_blank'>View CV</a></p>
    
</div>
";


                        }
                    } else {
                        echo "<p style='text-align:center;font-size:1.3rem;'>No accepted applications found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
} else {
    echo '<script>alert("Please login first"); window.location.href = "index.php";</script>';
}
?>