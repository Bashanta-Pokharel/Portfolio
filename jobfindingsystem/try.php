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

$username = $_SESSION["username"];

// ---------------------
// Initialize Variables
// ---------------------
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$title = $description = $location = $qualification = $salary = $category = $expirydate = '';
$image = '';

// ---------------------
// Capture location from map.php (GET method)
// ---------------------
if (isset($_GET['location'])) {
    $location = $_GET['location'];
}

// ---------------------
// Fetch Job Categories Dynamically
// ---------------------
$job_categories = [];
$cat_result = mysqli_query($con, "SELECT name FROM job_categories ORDER BY name ASC");
while ($cat_row = mysqli_fetch_assoc($cat_result)) {
    $job_categories[] = $cat_row['name'];
}

// ---------------------
// Get Company ID
// ---------------------
$cid = null;
$sql_company = "SELECT cid FROM company WHERE username = '$username'";
$result_company = mysqli_query($con, $sql_company);

if ($result_company && mysqli_num_rows($result_company) === 1) {
    $company_data = mysqli_fetch_assoc($result_company);
    $cid = $company_data['cid'];
} else {
    echo "<script>alert('Company not found!'); window.location='logout.php';</script>";
    exit;
}

// ---------------------
// Handle Add Job
// ---------------------
if (isset($_POST["addjob"])) {

    $title = $_POST["title"];
    $description = $_POST["description"];
    $location = $_POST["location"]; // this now comes from readonly input
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
    if (empty($_FILES["file"]["name"])) $error6 = "*Job image is required";
    if (empty($category)) $error7 = "*Please select a category";
    if (empty($expirydate)) $error8 = "*Please select expiry date";

    if (!empty($salary) && is_numeric($salary) && $salary < 0) {
        $error5 = "*Salary cannot be negative";
    }

    $currentDate = date("Y-m-d");
    if (!empty($expirydate) && $expirydate <= $currentDate) {
        $error8 = "*Expiry date must be greater than today's date";
    }

    // Insert Job if no errors
    if (
        empty($error1) && empty($error2) && empty($error3) &&
        empty($error4) && empty($error5) && empty($error6) &&
        empty($error7) && empty($error8)
    ) {

        $target_dir = "images/";
        $image = time() . "_" . basename($_FILES["file"]["name"]); // rename for uniqueness
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

            $sql = "INSERT INTO jobs 
                (title, description, location, qualification, salary, image, username, openeddate, expirydate, category, company_id)
                VALUES 
                ('$title', '$description', '$location', '$qualification', '$salary', '$image', '$username', CURRENT_TIMESTAMP(), '$expirydate', '$category', '$cid')";

            if (mysqli_query($con, $sql)) {
                echo "<script>alert('Job added successfully!'); window.location='companyhomepage.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error adding job to database');</script>";
            }

        } else {
            echo "<script>alert('Error uploading image');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Panel - Add Job</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.5em;">Company Panel</div>
        <div class="loginbuttons">
            <button>Profile</button>
            <a href="logout.php"><button>Logout</button></a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav-links">
            <li><a href="companyhomepage.php">Home</a></li>
            <li><a href="companyaddjob.php" class="active">Add Jobs</a></li>
            <li><a href="companyrecivedrequest.php">Received Requests</a></li>
            <li><a href="companyaccepteduser.php">Accepted User</a></li>
            <li><a href="companydeclinerequest.php">Declined User</a></li>
            <li><a href="companyprofile.php">Profile</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h1 style="text-align:center;">Add New Job</h1>

            <form method="post" class="login-form" enctype="multipart/form-data">

                <label class="form-label">Job Title</label>
                <input type="text" name="title" class="form-input" value="<?php echo $title; ?>">
                <div class="error"><?php echo $error1; ?></div>

                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="4"><?php echo $description; ?></textarea>
                <div class="error"><?php echo $error2; ?></div>

                <label class="form-label">Selected Location</label>
                <input class="form-input" type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" readonly>
                <a href="map.php">
                    <button type="button" class="btn">Select Location from Map</button>
                </a>
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

                <label class="form-label">Upload Job Image</label>
                <input type="file" name="file" class="form-input">
                <div class="error"><?php echo $error6; ?></div>

                <label class="form-label">Expiry Date</label>
                <input type="date" name="expirydate" class="form-input" value="<?php echo $expirydate; ?>">
                <div class="error"><?php echo $error8; ?></div>

                <label class="form-label">Username</label>
                <input type="text" class="form-input" value="<?php echo $username; ?>" readonly>

                <button type="submit" name="addjob" class="btn">Add Job</button>

            </form>
        </div>
    </div>

</body>

</html>
