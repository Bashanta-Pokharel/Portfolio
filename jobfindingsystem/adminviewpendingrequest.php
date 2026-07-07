<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; // DB connection

    // Count total pending applications
    $total_query = mysqli_query($con, "SELECT COUNT(*) as total FROM jobapplication");
    $total_pending = mysqli_fetch_assoc($total_query)['total'];

    // Fetch all applications
    $sql = "SELECT * FROM jobapplication ORDER BY id DESC";
    $result = mysqli_query($con, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>JFS - All Applications</title>
        <link rel="stylesheet" href="style3.css">
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding System</div>
            <div style="text-align:center; font-size: 1.8em;">Admin Panel</div>
            <div class="loginbuttons">
                <a href="logout.php" style="color: white; text-decoration: none;"><button>Logout</button></a>
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
                <li><a href="adminviewpendingrequest.php" class="active">All Pending Applications</a></li>
                <li><a href="allacceptedapplicant.php">All Accepted Applicants</a></li>
                <li><a href="allrejectedapplicant.php">All Rejected Applicants</a></li>
                <li><a href="addcategory.php">Add Category</a></li>

            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content">
                <h2>All Pending Job Applications</h2>
                <h3 style="font-weight:bold; margin-bottom:15px;">Total Pending Applications: <?php echo $total_pending; ?>
                </h3>

                <div class="jobs">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Fetch job info
                            $job_title = "Unknown";
                            $company_name = "Unknown";
                            $job_id = $row['job_id'];
                            $job_result = mysqli_query($con, "SELECT title, company_id FROM jobs WHERE id='$job_id'");
                            if ($job_result && mysqli_num_rows($job_result) > 0) {
                                $job_data = mysqli_fetch_assoc($job_result);
                                $job_title = $job_data['title'];
                                $company_id = $job_data['company_id'];
                                $photo = $row['photo'];
                                $photoTag = (!empty($photo) && file_exists("photos/" . $photo))
                                    ? "<img src='photos/{$photo}' alt='{$row['fullname']}' width='150' style='border-radius:8px; margin-bottom:10px;'>"
                                    : "<span>No Photo</span>";


                                $company_result = mysqli_query($con, "SELECT company_name FROM company WHERE cid='$company_id'");
                                if ($company_result && mysqli_num_rows($company_result) > 0) {
                                    $company_data = mysqli_fetch_assoc($company_result);
                                    $company_name = $company_data['company_name'];
                                }
                            }

                            echo "
                        <div class='job-card'>
                            {$photoTag}
                            <h3>{$row['fullname']}</h3>
                            <p><strong>Job Title:</strong> {$job_title}</p>
                            <p><strong>Applied Company:</strong> {$company_name}</p>
                            <p><strong>Email:</strong> {$row['email']}</p>
                            <p><strong>Phone:</strong> {$row['phone']}</p>
                            <p><strong>Address:</strong> {$row['address']}</p>
                            <p><strong>Skills:</strong> {$row['skills']}</p>
                            <p><strong>Experience:</strong> {$row['experiences']}</p>
                            <p><strong>Applied Date:</strong> " . date("d-m-Y", strtotime($row['applied_date'])) . "</p>
                            <p><strong>CV:</strong> <a href='cvs/{$row['cv']}' target='_blank'>View CV</a></p>

                           
                        </div>
                        ";
                        }
                    } else {
                        echo "<p style='text-align:center; font-size:1.3rem;'>No job applications found.</p>";
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