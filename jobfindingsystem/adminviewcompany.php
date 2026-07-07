<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>
        alert("Please login first");
        window.location.href = "index.php";
    </script>';
    exit;
}

// Total approved companies
$total_companies_query = mysqli_query(
    $con,
    "SELECT COUNT(*) AS total FROM company WHERE status='approved'"
);
$total_companies = mysqli_fetch_assoc($total_companies_query)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JFS | Approved Companies</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

<!-- Header -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>
    <div class="loginbuttons">
        <a href="logout.php"><button>Logout</button></a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="adminhomepage.php">Home</a></li>
        <li><a href="adminviewcompany.php" class="active">View Companies</a></li>
        <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
        <li><a href="adminviewuser.php">View Users</a></li>
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
            <p>Welcome, <span><?php echo $_SESSION["username"]; ?></span></p>
        </div>

        <h1>Approved Companies</h1>

        <p style="font-weight:bold; margin-bottom:15px;">
            Total Approved Companies: <?php echo $total_companies; ?>
        </p>

        <div class="jobs">
        <?php
        $result = mysqli_query($con, "SELECT * FROM company WHERE status='approved'");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='job-card'>
                    <h3>{$row['company_name']}</h3>

                    <p><strong>Username:</strong> {$row['username']}</p>
                    <p><strong>Email:</strong> {$row['email']}</p>
                    <p><strong>Address:</strong> {$row['address']}</p>
                    <p><strong>PAN:</strong> {$row['company_pan']}</p>
                    <p><strong>License:</strong> {$row['company_license']}</p>
                    <p><strong>Company Type:</strong> {$row['company_type']}</p>
                    <p style='color:green; font-weight:bold;'>Status: Approved</p>

                    <div style='margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;'>
                        <button class='btn'
                            onclick=\"window.location.href='adminupdatecompany.php?cid={$row['cid']}'\">
                            Edit
                        </button>

                        <button class='btn'
                            onclick=\"window.location.href='adminviewcompanyjobs.php?cid={$row['cid']}'\">
                            Show Jobs
                        </button>

                        <button class='btn'
                            onclick=\"if(confirm('Are you sure?')) window.location.href='admindeletecompany.php?cid={$row['cid']}';\">
                            Delete
                        </button>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p style='font-size:1.5rem;text-align:center;'>No approved companies found.</p>";
        }
        ?>
        </div>

    </div>
</div>

</body>
</html>
