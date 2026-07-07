<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>alert("Please login first"); window.location.href="admin.php";</script>';
    exit;
}

// Count total users
$total_users_query = mysqli_query($con, "SELECT COUNT(*) as total FROM user");
$total_users = mysqli_fetch_assoc($total_users_query)['total'];

// Fetch all users
$user_query = mysqli_query($con, "SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JFS | View Users</title>
<link rel="stylesheet" href="style3.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>
    <div class="loginbuttons">
        <a href="logout.php" style="color:white; text-decoration:none;"><button>Logout</button></a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="adminhomepage.php">Home</a></li>
        <li><a href="adminviewcompany.php">View Companies</a></li>
        <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
        <li><a href="adminviewuser.php" class="active">View Users</a></li>
        <li><a href="adminviewjobs.php">View Jobs</a></li>
        <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
        <li><a href="allacceptedapplicant.php">All Accepted Applicants</a></li>
        <li><a href="allrejectedapplicant.php">All Rejected Applicants</a></li>
        <li><a href="addcategory.php">Add Category</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content">
        <div class="welcome">
            <p>Welcome, <span><?php echo $_SESSION["username"]; ?></span>. You are logged in to the Job Finding System.</p>
        </div>

        <h1>Registered Users</h1>
        <p style="font-weight:bold; margin-bottom:15px;">Total Users: <?php echo $total_users; ?></p>

        <div class="jobs">
            <?php
            if (mysqli_num_rows($user_query) > 0) {
                while ($user = mysqli_fetch_assoc($user_query)) {
                    echo "
                    <div class='job-card'>
                        <h3>{$user['fname']} {$user['lname']}</h3>
                        <p><strong>Username:</strong> {$user['username']}</p>
                        <p><strong>Email:</strong> {$user['email']}</p>
                        <p><strong>Gender:</strong> {$user['gender']}</p>
                        <p><strong>Qualification:</strong> {$user['qualification']}</p>
                        <p><strong>Skills Releted TO:</strong> {$user['skills']}</p>

                        <div style='margin-top:12px;'>
                            <button class='btn' style='display:block; margin-bottom:6px;' onclick=\"window.location.href='adminupdateuser.php?uid={$user['uid']}'\">Edit</button>
                            <button class='btn' style='display:block;' onclick=\"if(confirm('Are you sure you want to delete this user?')) window.location.href='admindeleteuser.php?uid={$user['uid']}';\">Delete</button>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p>No users registered yet.</p>";
            }
            ?>
        </div>

    </div>
</div>

</body>
</html>
