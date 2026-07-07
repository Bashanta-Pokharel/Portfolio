<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo "<script>alert('Please login first'); window.location='index.php';</script>";
    exit;
}

$username = $_SESSION["username"];

// Step 1: Fetch declined applications for this user
$sql_dec = "SELECT * FROM declined_application WHERE username = '$username' ORDER BY rejected_at DESC";
$res_dec = mysqli_query($con, $sql_dec);
if (!$res_dec) {
    echo "Error fetching declined applications: " . mysqli_error($con);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Declined Jobs</title>
  <link rel="stylesheet" href="style3.css">
</head>
<body>
  <div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">User Panel</div>
    <div class="loginbuttons">
      <button><a href="userprofile.php" style="color: white; text-decoration: none;">Profile</a></button>
      <a href="logout.php" style="color: white; text-decoration: none;"><button>Logout</button></a>
    </div>
  </div>

  <div class="sidebar">
    <ul class="nav-links">
      <li><a href="userhomepage.php">Home</a></li>
      <li><a href="userviewjobs.php">View Jobs</a></li>
      <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
      <li><a href="useraccepted.php">View Accepted Jobs</a></li>
      <li><a href="userrejected.php" class="active">View Declined Jobs</a></li>
      <li><a href="userprofile.php">Profile</a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="content">
      <div class="welcome">
        <p>Welcome, <span><?php echo ($username); ?></span>! Here are your declined job(s):</p>
      </div>

      <h1>Declined Job Listings</h1>
      <div class="jobs">
        <?php
        if (mysqli_num_rows($res_dec) > 0) {
            while ($dec = mysqli_fetch_assoc($res_dec)) {
                $job_id = $dec['job_id'];
                $company_id = $dec['cid']; // stored when declining

                // Step 2: Fetch job details (optional)
                $sql_job = "SELECT * FROM jobs WHERE id = '$job_id'";
                $res_job = mysqli_query($con, $sql_job);
                $job = mysqli_fetch_assoc($res_job);

                // Step 3: Fetch company details
                $sql_cmp = "SELECT * FROM company WHERE cid = '$company_id'";
                $res_cmp = mysqli_query($con, $sql_cmp);
                $company = mysqli_fetch_assoc($res_cmp);
                ?>
                <div class="job-card" style="border: 2px solid red;">
                  <?php if ($job): ?>
                    <h3 style="color:red;"><?php echo ($job['title']); ?></h3>
                    <p><strong>Description:</strong> <?php echo ($job['description']); ?></p>
                  <?php else: ?>
                    <h3>Job #<?php echo ($job_id); ?></h3>
                  <?php endif; ?>

                  <p><strong>Declined On:</strong> <?php echo date("d-m-Y", strtotime($dec['rejected_at'])); ?></p>
                  <hr>
                  <h4 style="color:red;">You were declined by the Company</h4><hr>
                  <?php if ($company): ?>
                    <p><strong>Company Name:</strong> <?php echo ($company['company_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo ($company['email']); ?></p>
                    <p><strong>Address:</strong> <?php echo ($company['address']); ?></p>
                    <p><strong>PAN:</strong> <?php echo ($company['company_pan']); ?></p>
                    <p><strong>License:</strong> <?php echo ($company['company_license']); ?></p>
                    <p><strong>Type:</strong> <?php echo ($company['company_type']); ?></p>
                  <?php else: ?>
                    <p>Company info not available.</p>
                  <?php endif; ?>

                  <hr>
                  <h4>Your Application Info</h4>
                  <p><strong>Full Name:</strong> <?php echo ($dec['fullname']); ?></p>
                  <p><strong>Email:</strong> <?php echo ($dec['email']); ?></p>
                  <p><strong>Phone:</strong> <?php echo ($dec['phone']); ?></p>
                  <p><strong>Address:</strong> <?php echo ($dec['address']); ?></p>
                  <p><strong>Skills:</strong> <?php echo ($dec['skills']); ?></p>
                  <p><strong>Experience:</strong> <?php echo ($dec['experiences']); ?></p>
                  <p><strong>CV:</strong> 
                    <a href="cvs/<?php echo ($dec['cv']); ?>" target="_blank">View CV</a>
                  </p>
                  <p><strong>Photo:</strong> 
                    <a href="photos/<?php echo ($dec['photo']); ?>" target="_blank">View Photo</a>
                  </p>
                  <button style='background-color:red; color:white;' class="btn">Rejected</button>
                </div>
                <?php
            }
        } else {
            echo "<p style='font-size:1.5rem;text-align:center;'>No declined jobs yet.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
