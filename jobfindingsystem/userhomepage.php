<?php
session_start();

if (!isset($_SESSION["username"])) {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
    exit;
}

include "database.php";

/* ---------- Get User Skills ---------- */
$username = $_SESSION["username"];
$user_query = mysqli_query($con, "SELECT skills FROM user WHERE username='$username'");
$user_data = mysqli_fetch_assoc($user_query);

/*
 Example skills:
 "Web Development, PHP, MySQL"
 We use first skill as category
*/
$user_skills = $user_data['skills'] ?? '';
$skill_array = explode(',', $user_skills);
$user_category = trim($skill_array[0]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JFS</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size:1.8em;">User Panel</div>

    <div class="loginbuttons">
        <a href="userprofile.php"><button>Profile</button></a>
        <a href="logout.php"><button>Logout</button></a>
    </div>
</div>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="userhomepage.php" class="active">Home</a></li>
        <li><a href="userviewjobs.php">View Jobs</a></li>
        <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
        <li><a href="useraccepted.php">View accepted jobs</a></li>
        <li><a href="userrejected.php">View Rejected Jobs</a></li>
        <li><a href="userprofile.php">Profile</a></li>
    </ul>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-content">
<div class="content">

<div class="welcome">
    <p>
        Welcome, <span><?php echo $_SESSION["username"]; ?></span>!  
        You are logged in to the Job Finding System.
    </p>
</div>

<!-- ================= JOBS BY SKILL ================= -->
<h1>Jobs According to Your Skills (<?php echo $user_category ?: "General"; ?>)</h1>

<?php
if (!empty($user_category)) {

    $skill_job_query = mysqli_query(
        $con,
        "SELECT * FROM jobs 
         WHERE category='$user_category' 
         ORDER BY openeddate DESC 
         LIMIT 6"
    );

    if ($skill_job_query && mysqli_num_rows($skill_job_query) > 0) {
        echo "<div class='jobs'>";

        while ($row = mysqli_fetch_assoc($skill_job_query)) {
            ?>
            <div class="job-card">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                <h3><?php echo $row['title']; ?></h3>
                <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <p><strong>Qualification:</strong> <?php echo $row['qualification']; ?></p>
                <p><strong>Salary:</strong> <?php echo $row['salary']; ?></p>
                <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                <p><strong>Posted On:</strong>
                    <?php echo date("d-m-Y", strtotime($row['openeddate'])); ?>
                </p>
                <p><strong>Expiry Date:</strong>
                    <?php echo date("d-m-Y", strtotime($row['expirydate'])); ?>
                </p>
                <button class="btn"
                    onclick="window.location.href='applyjob.php?job_id=<?php echo $row['id']; ?>'">
                    Apply
                </button>
            </div>
            <?php
        }

        echo "</div>";
    } else {
        echo "<p style='color:gray;'>No jobs found matching your skills.</p>";
    }
} else {
    echo "<p style='color:gray;'>Please update your skills in profile.</p>";
}
?>

<hr style="margin:40px 0;">

<!-- ================= RECENT JOBS ================= -->
<h1>Recent Jobs</h1>

<?php
$recent_query = mysqli_query(
    $con,
    "SELECT * FROM jobs ORDER BY openeddate DESC LIMIT 6"
);

if ($recent_query && mysqli_num_rows($recent_query) > 0) {
    echo "<div class='jobs'>";

    while ($row = mysqli_fetch_assoc($recent_query)) {
        ?>
        <div class="job-card">
            <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
            <h3><?php echo $row['title']; ?></h3>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Qualification:</strong> <?php echo $row['qualification']; ?></p>
            <p><strong>Salary:</strong> <?php echo $row['salary']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Posted On:</strong>
                <?php echo date("d-m-Y", strtotime($row['openeddate'])); ?>
            </p>
            <p><strong>Expiry Date:</strong>
                <?php echo date("d-m-Y", strtotime($row['expirydate'])); ?>
            </p>
            <button class="btn"
                onclick="window.location.href='applyjob.php?job_id=<?php echo $row['id']; ?>'">
                Apply
            </button>
        </div>
        <?php
    }

    echo "</div>";
} else {
    echo "<p style='font-size:1.2rem; color:gray;'>No recent jobs available.</p>";
}
?>

</div>
</div>

</body>
</html>
