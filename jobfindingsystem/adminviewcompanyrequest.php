<?php
session_start();

if (!isset($_SESSION["username"])) {
    echo '<script>alert("Please login first"); window.location.href="index.php";</script>';
    exit;
}

include "database.php";

// Fetch pending companies
$result = mysqli_query($con, "SELECT * FROM company WHERE status='pending'");
$total_pending = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JFS | Company Requests</title>

<!-- Main layout CSS -->
<link rel="stylesheet" href="style3.css">


</head>

<body>

<!-- Header -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size:1.8em;">Admin Panel</div>
    <div class="loginbuttons">
        <a href="logout.php"><button>Logout</button></a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="adminhomepage.php">Home</a></li>
        <li><a href="adminviewcompany.php">View Companies</a></li>
        <li><a href="adminviewcompanyrequest.php" class="active">Company Requests</a></li>
        <li><a href="adminviewuser.php">View Users</a></li>
        <li><a href="adminviewjobs.php">View Jobs</a></li>
        <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
        <li><a href="allacceptedapplicant.php">All Accepted Applicant</a></li>
        <li><a href="allrejectedapplicant.php">All Rejected Applicant</a></li>
        <li><a href="addcategory.php">Add Category</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
<div class="content">

<h2>Pending Company Requests</h2>

<p style="font-weight:bold; margin-bottom:15px;">
    Total Pending Requests: <?php echo $total_pending; ?>
</p>

<!-- ✅ CUSTOM STYLED TABLE -->
<table class="custom-table">
<tr>
    <th>Company Name</th>
    <th>Email</th>
    <th>PAN</th>
    <th>License</th>
    <th>Category</th>
    <th>Action</th>
</tr>

<?php
if ($total_pending > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <tr>
            <td>{$row['company_name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['company_pan']}</td>
            <td>{$row['company_license']}</td>
            <td>{$row['company_type']}</td>
            <td>
                <a href='approvecompany.php?cid={$row['cid']}'>
                    <button class='action-btn edit-btn'>Approve</button>
                </a>
                <a href='rejectcompany.php?cid={$row['cid']}'
                   onclick=\"return confirm('Reject this company?');\">
                    <button class='action-btn delete-btn'>Reject</button>
                </a>
            </td>
        </tr>
        ";
    }
} else {
    echo "
    <tr>
        <td colspan='6' style='text-align:center; font-size:1.2rem;'>
            No pending company requests
        </td>
    </tr>
    ";
}
?>
</table>

</div>
</div>

</body>
</html>
