<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php";

    // Fetch categories from database
    $cat_result = mysqli_query($con, "SELECT * FROM job_categories ORDER BY name ASC");
    $job_categories = [];
    while ($cat_row = mysqli_fetch_assoc($cat_result)) {
        $job_categories[] = $cat_row['name'];
    }

    $selectedCategory = '';
    if (isset($_POST['Filter'])) {
        $selectedCategory = $_POST['category'];
        if ($selectedCategory != 'all') {
            $sql = "SELECT * FROM jobs WHERE category='$selectedCategory' ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM jobs ORDER BY id DESC";
        }
    } else {
        $sql = "SELECT * FROM jobs ORDER BY id DESC";
    }

    $result = mysqli_query($con, $sql);

    // Total jobs
    $total_jobs = mysqli_num_rows(mysqli_query($con, "SELECT * FROM jobs"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JFS | View Jobs</title>
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
        <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
        <li><a href="adminviewuser.php">View Users</a></li>
        <li><a href="adminviewjobs.php" class="active">View Jobs</a></li>
        <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
        <li><a href="allacceptedapplicant.php">All Accepted Applicant</a></li>
        <li><a href="allrejectedapplicant.php">All Rejected Applicant</a></li>
        <li><a href="addcategory.php">Add Category</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
<div class="content">

<h2>Manage Posted Jobs</h2>

<!-- Total Jobs Card -->
<h3 style="font-weight:bold; margin-bottom:15px;">Total Jobs: <?php echo $total_jobs; ?></h3>

<!-- Filter -->
<div class="filter-container">
<form method="post">
    <label><strong>Select Job Category:</strong></label>
    <select name="category">
        <option value="all">-- All Categories --</option>
        <?php foreach ($job_categories as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php if ($selectedCategory==$cat) echo "selected"; ?>>
                <?php echo $cat; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="Filter" value="Filter" class="submitbtn">
</form>
</div>

<!-- JOB CARDS -->
<div class="jobs">
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        // Get company name
        $company_name = "Unknown";
        $company_q = mysqli_query($con, "SELECT company_name FROM company WHERE cid='{$row['company_id']}'");
        if ($company_q && mysqli_num_rows($company_q) > 0) {
            $company_data = mysqli_fetch_assoc($company_q);
            $company_name = $company_data['company_name'];
        }

        echo "
<div class='job-card'>
    <img src='images/{$row['image']}' alt='{$row['title']}'>
    <h3>{$row['title']}</h3>
    <p><strong>Company:</strong> {$company_name}</p>
    <p><strong>Responsibilities:</strong> {$row['description']}</p>
    <p><strong>Location:</strong> {$row['location']}</p>
    <p><strong>Qualification:</strong> {$row['qualification']}</p>
    <p><strong>Salary:</strong> {$row['salary']}</p>
    <p><strong>Category:</strong> {$row['category']}</p>
    <p><strong>Posted On:</strong> ".date("d-m-Y",strtotime($row['openeddate']))."</p>
    <p><strong>Expiry Date:</strong> ".date("d-m-Y",strtotime($row['expirydate']))."</p>

    <div style='margin-top:12px;'>
        <button class='btn' style='display:block; margin-bottom:6px;' onclick=\"window.location.href='admineditjobs.php?job_id={$row['id']}'\">Edit</button>
        <button class='btn' style='display:block;' onclick=\"if(confirm('Delete this job?')) window.location.href='admindeletejobs.php?job_id={$row['id']}';\">Delete</button>
    </div>
</div>
";
    }
} else {
    echo "<p style='font-size:1.5rem;text-align:center;'>No jobs available in this category.</p>";
}
?>
</div>

</div>
</div>

</body>
</html>

<?php
} else {
    echo '<script>alert("Please login first"); window.location.href="index.php";</script>';
}
?>
