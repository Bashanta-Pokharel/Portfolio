<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; // DB connection
    $username = $_SESSION['username'];

    // Step 1: Get all job applications of the current user
    $sql_applications = "SELECT * FROM jobapplication WHERE username='$username' ORDER BY applied_date DESC";
    $res_applications = mysqli_query($con, $sql_applications);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Applied Jobs</title>
        <!-- <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style2.css"> -->
        <link rel="stylesheet" href="style3.css">
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding System</div>
            <div style="text-align:center; font-size: 1.8em;">User Panel</div>

            <div class="loginbuttons">
                <a href="userprofile.php" style="color: white; text-decoration: none;"><button>Profile</button></a>
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <button>Logout</button>
                </a>
            </div>
        </div>

        <!-- Sidebar Navbar -->
        <div class="sidebar">
            <ul class="nav-links">
                <li><a href="userhomepage.php">Home</a></li>
                <li><a href="userviewjobs.php">View Jobs</a></li>
                <li><a href="userjobrequeststatus.php" class="active">Job Request Status</a></li>
                <li><a href="useraccepted.php">View accepted jobs</a></li>
                <li><a href="userrejected.php" >View Rejected Jobs</a></li>
                <li><a href="userprofile.php">Profile</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="content">
                <div class="welcome">
                    <p>Welcome, <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>! You are logged in to
                        the Job Finding System to the job request status page</p>
                </div>

                <h1>Job Request Staus Check</h1>
                <div class="jobs">
                    <?php
                    if (mysqli_num_rows($res_applications) > 0) {
                        while ($app = mysqli_fetch_assoc($res_applications)) {
                            // Step 2: For each application, fetch the job details
                            $job_id = $app['job_id'];
                            $sql_job = "SELECT * FROM jobs WHERE id='$job_id'";
                            $res_job = mysqli_query($con, $sql_job);
                            $job = mysqli_fetch_assoc($res_job);

                            echo "<div class='job-card'>
                            <img src='images/{$job['image']}' alt='{$job['title']}'>
                            <h3>{$job['title']}</h3>
                            <p><strong>Description:</strong> {$job['description']}</p>
                            <p><strong>Location:</strong> {$job['location']}</p>
                            <p><strong>Qualification:</strong> {$job['qualification']}</p>
                            <p><strong>Salary:</strong> {$job['salary']}</p>
                            <p><strong>Category:</strong> {$job['category']}</p>
                            <p><strong>Full Name:</strong> {$app['fullname']}</p>
                            <p><strong>Email:</strong> {$app['email']}</p>
                            <p><strong>Phone:</strong> {$app['phone']}</p>
                            <p><strong>Address:</strong> {$app['address']}</p>
                            <p><strong>Skills:</strong> {$app['skills']}</p>
                            <p><strong>Experience:</strong> {$app['experiences']}</p>
                            <p><strong>CV:</strong> <a href='cvs/{$app['cv']}' target='_blank'>View CV</a></p>
                            <p><strong>Photo:</strong> <a href='photos/{$app['photo']}' target='_blank'>View Photo</a></p>
                            <p><strong>Job Posted On:</strong> " . date("d-m-Y", strtotime($job['openeddate'])) . "</p>
                            <p><strong>Expiry Date:</strong> " . date("d-m-Y", strtotime($job['expirydate'])) . "</p>
                              <button class='btn' style='background-color: green;'>Pending</button>

                            <button class='btn' onclick=\"window.location.href='viewapplication.php?app_id={$app['id']}'\">
                                Applied On: " . date("d-m-Y", strtotime($app['applied_date'])) . "
                            </button>
                            
                        </div>";
                        }
                    } else {
                        echo "<p style='font-size:1.5rem;text-align:center;'>You have not applied to any jobs yet.</p>";
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