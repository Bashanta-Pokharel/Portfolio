<?php
session_start();   
include "database.php";

// Check if company is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login first!'); window.location='company.php';</script>";
    exit;
}

$username = $_SESSION['username'];

// Fetch company details
$query = "SELECT * FROM company WHERE username = '$username'";
$result = mysqli_query($con, $query);
$company = mysqli_fetch_assoc($result);

if (!$company) {
    echo "<script>alert('Company not found!'); window.location='company.php';</script>";
    exit;
}

// Fetch job categories dynamically
$cat_result = mysqli_query($con, "SELECT * FROM job_categories ORDER BY name ASC");
$job_categories = [];
while ($cat_row = mysqli_fetch_assoc($cat_result)) {
    $job_categories[] = $cat_row['name'];
}

$error1 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$success = '';

if (isset($_POST['update'])) {
    $cname = $_POST['cname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $pan = $_POST['pan'];
    $license = $_POST['license'];
    $category = $_POST['category'];

    // Validation
    if (empty($cname)) $error1 = "*Company Name is required";
    if (empty($email)) $error4 = "*Email is required";
    if (empty($address)) $error8 = "*Address is required";
    if (empty($pan)) $error5 = "*Company PAN is required";
    if (empty($license)) $error6 = "*Company License Number is required";
    if (empty($category)) $error7 = "*Please select Company Category";

    if (empty($error1) && empty($error3) && empty($error4) && empty($error5) && empty($error6) && empty($error7) && empty($error8)) {
        
        // Optional password update
        $password_sql = '';
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $password_sql = ", password = '$hashed_password'";
        }

        $update_sql = "UPDATE company SET 
            company_name = '$cname',
            email = '$email',
            address = '$address',
            company_pan = '$pan',
            company_license = '$license',
            company_type = '$category'
            $password_sql
            WHERE username = '$username'";

        if (mysqli_query($con, $update_sql)) {
            // Refresh data
            $result = mysqli_query($con, "SELECT * FROM company WHERE username = '$username'");
            $company = mysqli_fetch_assoc($result);

            // Show JavaScript alert for success
            echo "<script>alert('Profile updated successfully!'); window.location.href='companyprofile.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error updating profile: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Company Panel - Edit Profile</title>
<link rel="stylesheet" href="style3.css">
</head>

<body>

<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">Company Panel</div>
    <div class="loginbuttons">
        <button>Profile</button>
        <a href="logout.php" style="color: white; text-decoration: none;">
            <button>Logout</button>
        </a>
    </div>
</div>

<div class="sidebar">
    <ul class="nav-links">
        <li><a href="companyhomepage.php">Home</a></li>
        <li><a href="companyaddjobs.php">Add Jobs</a></li>
        <li><a href="companyrecivedrequest.php">Received Requests</a></li>
        <li><a href="companyaccepteduser.php">Accepted User</a></li>
        <li><a href="companydeclinerequest.php">Declined User</a></li>
        <li><a href="companyprofile.php" class="active">Profile</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="content">
        <form method="post" class="login-form">
            <h1>Edit Profile</h1>

            <label class="form-label">Company Name</label>
            <input type="text" name="cname" class="form-input" value="<?php echo $company['company_name']; ?>">
            <div class="error"><?php echo $error1; ?></div>

            <label class="form-label">Username (Not Editable)</label>
            <input type="text" class="form-input" value="<?php echo $company['username']; ?>" disabled>

            <label class="form-label">New Password (Leave blank to keep current)</label>
            <input type="password" name="password" class="form-input" placeholder="Enter new password">

            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="<?php echo $company['email']; ?>">
            <div class="error"><?php echo $error4; ?></div>

            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-input" value="<?php echo $company['address']; ?>">
            <div class="error"><?php echo $error8; ?></div>

            <label class="form-label">Company PAN</label>
            <input type="text" name="pan" class="form-input" value="<?php echo $company['company_pan']; ?>" readonly>
            <div class="error"><?php echo $error5; ?></div>

            <label class="form-label">Company License Number</label>
            <input type="text" name="license" class="form-input" value="<?php echo $company['company_license']; ?>" readonly>
            <div class="error"><?php echo $error6; ?></div>

            <label class="form-label">Company Category</label>
            <select name="category" class="form-input">
                <option value="">-- Select Category --</option>
                <?php foreach($job_categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php if($company['company_type']==$cat) echo "selected"; ?>>
                        <?php echo $cat; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="error"><?php echo $error7; ?></div>

            <button type="submit" name="update" class="btn">Update Profile</button><br><br>
            <button type="button" class="btn" onclick="window.location.href='company_dashboard.php'">Go Back</button>
        </form>
    </div>
</div>

</body>
</html>
