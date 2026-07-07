<?php
include "database.php"; // DB connection

// Fetch all jobs
$sql = "SELECT * FROM jobs ORDER BY id DESC";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Job Finding System</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <div class="headerlogin">
        <h1>Online Job Finding System</h1>
        <h2>System</h2>
    </div>
    <div class="navbar">
        <div class="nav-container">
            <div class="nav-item" style="background-color: #e8f0fe;
            color: #1a73e8">Home</div>
            <a href="jobs.php">
                <div class="nav-item">Jobs</div>
            </a>
            <a href="admin.php">
                <div class="nav-item">Admin</div>
            </a>
            <a href="user.php">
                <div class="nav-item">user</div>
            </a>
            <a href="company.php">
                <div class="nav-item">Company</div>
            </a>
        </div>
    </div>
    <div class="welcome-section">
        <h2>Welcome to home page</h2>
        <p>Discover a jobs as per the need </p>
        <a href="jobs.php" class="btn">Explore jobs</a>
    </div>
    <div>
        
    </div>
    <div class="jobs">
        <?php
       $count = 0;
while ($row = mysqli_fetch_assoc($result)) {
    if ($count >= 3) break; // stop after 3 jobs
    echo "
        <div class='job-card'>
            <img src='images/{$row['image']}' alt='{$row['title']}'>
            <h3>{$row['title']}</h3>
            <p><strong>Description:</strong> {$row['description']}</p>
            <p><strong>Location:</strong> {$row['location']}</p>
            <p><strong>Qualification:</strong> {$row['qualification']}</p>
            <p><strong>Salary:</strong> {$row['salary']}</p>
            <p><strong>Category:</strong> {$row['category']}</p>

            <p><strong>Posted On:</strong> " . date("d-m-Y", strtotime($row['openeddate'])) . "</p>
            <p><strong>Expiry Date:</strong> " . date("d-m-Y", strtotime($row['expirydate'])) . "</p>
            <button class='btn' onclick=\"alert('Please login first'); window.location.href='userregister.php';\">Apply</button>
        </div>
    ";
    $count++;
}

        ?>
    </div>
    <div class="footer">
        <p style="font-size: 1.9rem; text-align: center;">
            &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
        </p>
    </div>
</body>

</html>