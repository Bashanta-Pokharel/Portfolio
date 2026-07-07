<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo "<script>alert('Please login first'); window.location='index.php';</script>";
    exit;
}

$username = $_SESSION["username"];

// Step 1: Fetch accepted applications for this user
$sql_acc = "SELECT * FROM accepted_application WHERE username = '$username' ORDER BY accepted_at DESC";
$res_acc = mysqli_query($con, $sql_acc);
if (!$res_acc) {
    echo "Error fetching accepted applications: " . mysqli_error($con);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accepted Jobs</title>
  <!-- <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="style2.css"> -->
  <link rel="stylesheet" href="style3.css">
</head>
<body>
  <div class="header">
    <div class="headername">Job Finding System</div>
      <div style="text-align:center; font-size: 1.8em;">User Panel</div>
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
      <li><a href="useraccepted.php" class="active">View Accepted Jobs</a></li>
      <li><a href="userrejected.php" >View Rejected Jobs</a></li>
      <li><a href="userprofile.php">Profile</a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="content">
      <div class="welcome">
        <p>Welcome, <span><?php echo ($username); ?></span> Here are your accepted job(s):</p>
      </div>

      <h1>Accepted Job Listings</h1>
      <div class="jobs" >
        <?php
        if (mysqli_num_rows($res_acc) > 0) {
            while ($acc = mysqli_fetch_assoc($res_acc)) {
                $job_id = $acc['job_id'];
                $company_id = $acc['cid'];  // you must have stored this when accepting

                // Step 2: Fetch job details (optional, if you want job info)
                $sql_job = "SELECT * FROM jobs WHERE id = '$job_id'";
                $res_job = mysqli_query($con, $sql_job);
                $job = mysqli_fetch_assoc($res_job);

                // Step 3: Fetch company details
                $sql_cmp = "SELECT * FROM company WHERE cid = '$company_id'";
                $res_cmp = mysqli_query($con, $sql_cmp);
                $company = mysqli_fetch_assoc($res_cmp);

                ?>
                <div class="job-card">
                  <?php if ($job): ?>
                    <h3><?php echo ($job['title']); ?></h3>
                    <p><strong>Description:</strong> <?php echo ($job['description']); ?></p>
                    <!-- add more job fields as needed -->
                  <?php else: ?>
                    <h3>Job #<?php echo ($job_id); ?></h3>
                  <?php endif; ?>

                  <p><strong>Accepted On:</strong> <?php echo date("d-m-Y", strtotime($acc['accepted_at'])); ?></p>
                  <hr>
                  <h4>you are accepted by the Company </h4><hr>
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
                  <p><strong>Full Name:</strong> <?php echo ($acc['fullname']); ?></p>
                  <p><strong>Email:</strong> <?php echo ($acc['email']); ?></p>
                  <p><strong>Phone:</strong> <?php echo ($acc['phone']); ?></p>
                  <p><strong>Address:</strong> <?php echo ($acc['address']); ?></p>
                  <p><strong>Skills:</strong> <?php echo ($acc['skills']); ?></p>
                  <p><strong>Experience:</strong> <?php echo ($acc['experiences']); ?></p>
                  <p><strong>CV:</strong> <a href="cvs/<?php echo ($acc['cv']); ?>" target="_blank">View CV</a></p>
                  <p><strong>Photo:</strong> <a href="photos/<?php echo ($acc['photo']); ?>" target="_blank">View Photo</a></p>
                  <button class="btn" name="accept">Accepted</button>
                </div>
                <?php
            }
        } else {
            echo "<p style='font-size:1.5rem;text-align:center;'>No accepted jobs yet.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
