<?php
session_start();
include "database.php"; // DB connection

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // ✅ Step 1: Get company ID
    $companyresult = mysqli_query($con, "SELECT cid FROM company WHERE username = '$username'");
    $cid = null;

    if ($companyresult && mysqli_num_rows($companyresult) === 1) {
        $companydata = mysqli_fetch_assoc($companyresult);
        $cid = $companydata['cid'];
    }

    $declinedApplicants = [];

    if ($cid !== null) {
        // ✅ Step 2: Get job IDs and titles for jobs posted by this company
        $jobs = []; // key = job_id, value = title
        $jobresult = mysqli_query($con, "SELECT id, title FROM jobs WHERE company_id = '$cid'");

        if ($jobresult && mysqli_num_rows($jobresult) > 0) {
            $jobid = [];

            while ($job = mysqli_fetch_assoc($jobresult)) {
                $jobid[] = $job['id'];
                $jobs[$job['id']] = $job['title'];
            }

            // ✅ Step 3: Fetch declined applicants for these jobs
            if (!empty($jobid)) {
                $jobidstr = implode(",", array_map('intval', $jobid)); // safe for SQL
                $applicantquery = "SELECT * FROM declined_application WHERE job_id IN ($jobidstr) ORDER BY rejected_at DESC";
                $applicantresult = mysqli_query($con, $applicantquery);

                if ($applicantresult && mysqli_num_rows($applicantresult) > 0) {
                    while ($row = mysqli_fetch_assoc($applicantresult)) {
                        $job_id = $row['job_id'];
                        $row['job_title'] = isset($jobs[$job_id]) ? $jobs[$job_id] : 'Unknown';
                        $declinedApplicants[] = $row;
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declined Applicants</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">Company Panel</div>
    <div class="loginbuttons">
        <button>Profile</button>
        <a href="logout.php" style="color: white; text-decoration: none;">
            <button>Logout</button>
        </a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="companyhomepage.php">Home</a></li>
        <li><a href="companyaddjobs.php">Add Jobs</a></li>
        <li><a href="companyrecivedrequest.php">Received Requests</a></li>
        <li><a href="companyaccepteduser.php">Accepted User</a></li>
        <li><a href="companydeclinerequest.php" class="active">Declined User</a></li>
        <li><a href="companyprofile.php">Profile</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content">
        <h2 style="text-align:center; margin-bottom:20px;">Declined Applicants</h2>
        <div class="jobs">
            <?php
            if (!empty($declinedApplicants)) {
                foreach ($declinedApplicants as $row) {
                    $photo = $row['photo'];
                    $cv = $row['cv'];
                    $fullname = $row['fullname'];
                    $email = $row['email'];
                    $phone = $row['phone'];
                    $address = $row['address'];
                    $skills = $row['skills'];
                    $experience = $row['experiences'];
                    $jobTitle = $row['job_title'];
                    $rejectedAt = date("d-m-Y", strtotime($row['rejected_at']));

                    $cvLink = !empty($cv) ? "<a href='cvs/{$cv}' target='_blank'>View CV</a>" : "No CV Provided";
                    $photoTag = (!empty($photo) && file_exists("photos/" . $photo)) 
                                ? "<img src='photos/{$photo}' alt='{$fullname}' width='150'>" 
                                : "<span>No Photo</span>";

                    echo "
                    <div class='job-card'style='border: 2px solid red;'>
                        <p><strong>Applicant Photo:</strong><br> $photoTag</p>
                        <p><strong>Full Name:</strong> $fullname</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Phone:</strong> $phone</p>
                        <p><strong>Address:</strong> $address</p>
                        <p><strong>Skills:</strong> $skills</p>
                        <p><strong>Experience:</strong> $experience</p>
                        <p><strong>Job Title:</strong> $jobTitle</p>
                        <p><strong>CV:</strong> $cvLink</p>
                        <p><strong>Declined On:</strong> $rejectedAt</p>
                        <button type='submit' name='decline' class='btn' style='background-color:red; color:white;'>Rejected</button>

                    </div>
                    ";
                }
            } else {
                echo "<p style='font-size:1.5rem;text-align:center;'>No declined applicants found.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
<?php
} else {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
}
?>
