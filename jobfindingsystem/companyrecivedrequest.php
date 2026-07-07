<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; 
    $company_username = $_SESSION['username'];


    $sql_company = "SELECT * FROM company WHERE username='$company_username'";
    $res_company = mysqli_query($con, $sql_company);

    if (!$res_company || mysqli_num_rows($res_company) !== 1) {
        echo "<script>alert('Company not found!'); window.location='logout.php';</script>";
        exit;
    }

    $company = mysqli_fetch_assoc($res_company);
    $company_id = $company['cid'];


    $sql_jobs = "SELECT * FROM jobs WHERE company_id='$company_id' ORDER BY id DESC";
    $res_jobs = mysqli_query($con, $sql_jobs);
    if (!$res_jobs) {
        echo "<script>alert('Error fetching jobs: " . mysqli_error($con) . "');</script>";
        $res_jobs = [];
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Received Job Applications</title>
        <!-- <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style2.css"> -->
        <link rel="stylesheet" href="style3.css">
    </head>

    <body>
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

        <div class="sidebar">
            <ul class="nav-links">
                <li><a href="companyhomepage.php">Home</a></li>
                <li><a href="companyaddjobs.php">Add Jobs</a></li>
                <li><a href="companyrecivedrequest.php" class="active">Received Requests</a></li>
                <li><a href="companyaccepteduser.php">Accepted User</a></li>
                <li><a href="companydeclinerequest.php">Declined User</a></li>
                <li><a href="companyprofile.php">Profile</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="content">
                <div class="welcome">
                    <p>Welcome, <span><?php echo ($company_username); ?></span>! You are logged in to the
                        Job Finding System.</p>
                </div>

                <h1 style="text-align:center; margin-bottom:20px;">Received Job Applications</h1>

                <div class="jobs">
                    <?php
                    $hasApplicants = false;

                    if (mysqli_num_rows($res_jobs) > 0) {
                        while ($job = mysqli_fetch_assoc($res_jobs)) {
                            $job_id = $job['id'];
                            $sql_applications = "SELECT * FROM jobapplication WHERE job_id='$job_id' ORDER BY applied_date DESC";
                            $res_applications = mysqli_query($con, $sql_applications);

                            if ($res_applications && mysqli_num_rows($res_applications) > 0) {
                                $hasApplicants = true;

                                while ($app = mysqli_fetch_assoc($res_applications)) {
                                    ?>
                                    <div class='job-card'>
                                        <h3>Job: <?php echo ($job['title']); ?></h3>
                                        <div class='applicant-card'>
                                            <p><strong>Applicant Photo:</strong><br>
                                                <img src='photos/<?php echo ($app['photo']); ?>'
                                                    alt='<?php echo ($app['fullname']); ?>' width='150'>
                                            </p>
                                            <p><strong>Full Name:</strong> <?php echo ($app['fullname']); ?></p>
                                            <p><strong>Email:</strong> <?php echo ($app['email']); ?></p>
                                            <p><strong>Phone:</strong> <?php echo ($app['phone']); ?></p>
                                            <p><strong>Address:</strong> <?php echo ($app['address']); ?></p>
                                            <p><strong>Skills:</strong> <?php echo ($app['skills']); ?></p>
                                            <p><strong>Experience:</strong> <?php echo ($app['experiences']); ?></p>
                                            <p><strong>CV:</strong> <a href='cvs/<?php echo ($app['cv']); ?>'
                                                    target='_blank'>View CV</a></p>
                                            <p><strong>Applied On:</strong> <?php echo date("d-m-Y", strtotime($app['applied_date'])); ?>
                                            </p>

                                            <form action='companyacceptrequest.php' method='post' >
                                                <input type='hidden' name='application_id' value='<?php echo $app['id']; ?>'>
                                                <input type='hidden' name='job_id' value='<?php echo $job_id; ?>'>
                                                <button type='submit' name='accept' class='btn'>Accept Request</button>
                                                
                                            </form>
                                            <form action='companydeclineuser.php' method='post'>
                                                <input type='hidden' name='application_id' value='<?php echo $app['id']; ?>'>
                                                <input type='hidden' name='job_id' value='<?php echo $job_id; ?>'>
                                          <button type='submit' name='decline' class='btn' style="background-color:red; color:white;">Decline Request</button>

                                                
                                            </form>

                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }

                        if (!$hasApplicants) {
                            echo "<p style='font-size:1.5rem;text-align:center;'>No job applications received yet.</p>";
                        }
                    } else {
                        echo "<p style='font-size:1.5rem;text-align:center;'>No jobs posted yet.</p>";
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