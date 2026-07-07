<?php
session_start();
include "database.php";

// ---------------------
// Check Login
// ---------------------
if (!isset($_SESSION["username"])) {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
    exit;
}

$username = $_SESSION["username"];

// ---------------------
// Initialize Variables
// ---------------------
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$title = $description = $location = $qualification = $salary = $category = $expirydate = $image = '';
$job_id = $_GET['job_id'] ?? '';

// ---------------------
// Fetch Job Categories Dynamically
// ---------------------
$job_categories = [];
$cat_result = mysqli_query($con, "SELECT name FROM job_categories ORDER BY name ASC");

while ($cat_row = mysqli_fetch_assoc($cat_result)) {
    $job_categories[] = $cat_row['name'];
}

// ---------------------
// Step 1: Fetch Job Details
// ---------------------
if (!empty($job_id)) {
    $sql = "SELECT * FROM jobs WHERE id = '$job_id'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $job = mysqli_fetch_assoc($result);
        $title = $job['title'];
        $description = $job['description'];
        $location = $job['location'];
        $qualification = $job['qualification'];
        $salary = $job['salary'];
        $category = $job['category'];
        $expirydate = $job['expirydate'];
        $image = $job['image'];
    } else {
        echo "<script>alert('Job not found!'); window.location='adminviewjobs.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request!'); window.location='adminviewjobs.php';</script>";
    exit;
}

// ---------------------
// Step 2: Handle Update
// ---------------------
if (isset($_POST["updatejob"])) {

    $title = $_POST["title"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $qualification = $_POST["qualification"];
    $salary = $_POST["salary"];
    $category = $_POST["category"];
    $expirydate = $_POST["expirydate"];

    // Validation
    if (empty($title)) $error1 = "*Job title is required";
    if (empty($description)) $error2 = "*Description is required";
    if (empty($location)) $error3 = "*Location is required";
    if (empty($qualification)) $error4 = "*Qualification is required";
    if (empty($salary)) $error5 = "*Salary is required";
    if (empty($category)) $error7 = "*Please select a category";
    if (empty($expirydate)) $error8 = "*Please select expiry date";

    // Image Upload (Optional)
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "images/";
        $new_image = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $new_image;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $image = $new_image;
        }
    }

    // Update Query
    if (
        empty($error1) && empty($error2) && empty($error3) &&
        empty($error4) && empty($error5) && empty($error7) && empty($error8)
    ) {
        $update_sql = "UPDATE jobs SET
                        title='$title',
                        description='$description',
                        location='$location',
                        qualification='$qualification',
                        salary='$salary',
                        category='$category',
                        expirydate='$expirydate',
                        image='$image'
                      WHERE id='$job_id'";

        if (mysqli_query($con, $update_sql)) {
            echo "<script>alert('Job updated successfully!'); window.location='adminviewjobs.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error updating job');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Edit Job</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <li><a href="adminviewcompany.php">View Companies</a></li>
        <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
        <li><a href="adminviewuser.php">View Users</a></li>
        <li><a href="adminviewjobs.php">View Jobs</a></li>
        <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
        <li><a href="allacceptedapplicant.php">All Accepted Applicant</a></li>
        <li><a href="allrejectedapplicant.php">All Rejected Applicant</a></li>
        <li><a href="addcategory.php">Add Category</a></li>
        <li><a href="#" class="active">Editing Job</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content">
        <h1 style="text-align:center;">Edit Job</h1>

        <form method="post" class="login-form" enctype="multipart/form-data">

            <label class="form-label">Job Title</label>
            <input type="text" name="title" class="form-input" value="<?php echo $title; ?>">
            <div class="error"><?php echo $error1; ?></div>

            <label class="form-label">Description</label>
            <textarea name="description" class="form-input" rows="4"><?php echo $description; ?></textarea>
            <div class="error"><?php echo $error2; ?></div>

            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-input" value="<?php echo $location; ?>">
            <div class="error"><?php echo $error3; ?></div>

            <label class="form-label">Qualification</label>
            <input type="text" name="qualification" class="form-input" value="<?php echo $qualification; ?>">
            <div class="error"><?php echo $error4; ?></div>

            <label class="form-label">Salary</label>
            <input type="text" name="salary" class="form-input" value="<?php echo $salary; ?>">
            <div class="error"><?php echo $error5; ?></div>

            <label class="form-label">Category</label>
            <select name="category" class="form-input">
                <option value="">-- Select Category --</option>
                <?php foreach ($job_categories as $cat) { ?>
                    <option value="<?php echo $cat; ?>" <?php if ($cat == $category) echo "selected"; ?>>
                        <?php echo $cat; ?>
                    </option>
                <?php } ?>
            </select>
            <div class="error"><?php echo $error7; ?></div>

            <label class="form-label">Current Job Image</label><br>
            <?php if (!empty($image)) { ?>
                <img src="images/<?php echo $image; ?>" style="width:120px;height:120px;border-radius:10px;">
            <?php } ?>
            <br><br>

            <label class="form-label">Upload New Image (Optional)</label>
            <input type="file" name="file" class="form-input">

            <label class="form-label">Expiry Date</label>
            <input type="date" name="expirydate" class="form-input" value="<?php echo $expirydate; ?>">
            <div class="error"><?php echo $error8; ?></div>

            <button type="submit" name="updatejob" class="btn">Update Job</button>

        </form>
    </div>
</div>

</body>
</html>
