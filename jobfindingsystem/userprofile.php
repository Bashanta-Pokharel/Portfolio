<?php
session_start();
include "database.php";

/* ---------- Check Login ---------- */
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login first!'); window.location='user.php';</script>";
    exit;
}

$username = $_SESSION['username'];

/* ---------- Fetch User Details ---------- */
$user_query = mysqli_query($con, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($user_query);

if (!$user) {
    echo "<script>alert('User not found!'); window.location='user.php';</script>";
    exit;
}

/* ---------- Fetch Job Categories ---------- */
$cat_result = mysqli_query($con, "SELECT * FROM job_categories ORDER BY name ASC");
$job_categories = [];
while ($row = mysqli_fetch_assoc($cat_result)) {
    $job_categories[] = $row['name'];
}

/* ---------- Errors ---------- */
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';

/* ---------- Update Profile ---------- */
if (isset($_POST['update'])) {

    $fname         = $_POST['fname'];
    $lname         = $_POST['lname'];
    $new_username  = $_POST['username'];
    $password      = $_POST['password'];
    $email         = $_POST['email'];
    $qualification = $_POST['qualification'];
    $gender        = $_POST['gender'] ?? '';
    $category      = $_POST['category'] ?? '';

    /* ---------- Validation ---------- */
    if (empty($fname))         $error1 = "*First name is required";
    if (empty($lname))         $error2 = "*Last name is required";
    if (empty($new_username))  $error3 = "*Username is required";
    if (empty($email))         $error5 = "*Email is required";
    if (empty($qualification)) $error6 = "*Qualification is required";
    if (empty($gender))        $error8 = "*Select gender";
    if (empty($category) || $category == "all")
                               $error7 = "*Select job expertise";

    /* ---------- Check Username ---------- */
    $check_user = mysqli_query(
        $con,
        "SELECT * FROM user 
         WHERE username='$new_username' 
         AND uid != '{$user['uid']}'"
    );
    if ($check_user && mysqli_num_rows($check_user) > 0) {
        $error3 = "*Username already taken";
    }

    /* ---------- Update ---------- */
    if (
        empty($error1) && empty($error2) && empty($error3) &&
        empty($error5) && empty($error6) &&
        empty($error7) && empty($error8)
    ) {

        $password_sql = '';
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $password_sql = ", password='$hashed_password'";
        }

        $update_sql = "UPDATE user SET
            fname='$fname',
            lname='$lname',
            username='$new_username',
            email='$email',
            qualification='$qualification',
            gender='$gender',
            skills='$category'
            $password_sql
            WHERE username='$username'";

        if (mysqli_query($con, $update_sql)) {
            $_SESSION['username'] = $new_username;
            echo "<script>alert('Profile updated successfully!'); window.location='userprofile.php';</script>";
            exit;
        } else {
            echo "<script>alert('Update failed!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - Edit Profile</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size:1.5em;">User Panel</div>
    <div class="loginbuttons">
        <a href="logout.php"><button>Logout</button></a>
    </div>
</div>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <ul class="nav-links">
        <li><a href="userhomepage.php">Home</a></li>
        <li><a href="userviewjobs.php">View Jobs</a></li>
        <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
        <li><a href="useraccepted.php">View accepted jobs</a></li>
        <li><a href="userrejected.php">View Rejected Jobs</a></li>
        <li><a href="userprofile.php" class="active">Profile</a></li>
    </ul>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-content">
<div class="content">

<form method="post" class="login-form">
<h1>Edit Profile</h1>

<label class="form-label">First Name</label>
<input type="text" name="fname" class="form-input" value="<?php echo $user['fname']; ?>">
<div class="error"><?php echo $error1; ?></div>

<label class="form-label">Last Name</label>
<input type="text" name="lname" class="form-input" value="<?php echo $user['lname']; ?>">
<div class="error"><?php echo $error2; ?></div>

<label class="form-label">Username</label>
<input type="text" name="username" class="form-input" value="<?php echo $user['username']; ?>">
<div class="error"><?php echo $error3; ?></div>

<label class="form-label">New Password (optional)</label>
<input type="password" name="password" class="form-input">

<label class="form-label">Email</label>
<input type="email" name="email" class="form-input" value="<?php echo $user['email']; ?>">
<div class="error"><?php echo $error5; ?></div>

<label class="form-label">Gender</label>
<div class="checkboxfont">
    <input type="radio" name="gender" value="Male"   <?php if($user['gender']=="Male") echo "checked"; ?>> Male
    <input type="radio" name="gender" value="Female" <?php if($user['gender']=="Female") echo "checked"; ?>> Female
    <input type="radio" name="gender" value="Other"  <?php if($user['gender']=="Other") echo "checked"; ?>> Other
</div>
<div class="error"><?php echo $error8; ?></div>

<label class="form-label">Qualification</label>
<select name="qualification" class="form-input">
    <option value="">-- Select Qualification --</option>
    <option value="High School" <?php if($user['qualification']=="High School") echo "selected"; ?>>High School</option>
    <option value="+2" <?php if($user['qualification']=="+2") echo "selected"; ?>>+2</option>
    <option value="Bachelor" <?php if($user['qualification']=="Bachelor") echo "selected"; ?>>Bachelor</option>
    <option value="Master" <?php if($user['qualification']=="Master") echo "selected"; ?>>Master</option>
    <option value="Other" <?php if($user['qualification']=="Other") echo "selected"; ?>>Other</option>
</select>
<div class="error"><?php echo $error6; ?></div>

<label class="form-label">Job Expertise</label>
<select name="category" class="form-input">
    <option value="all">-- Select Job Expertise --</option>
    <?php foreach($job_categories as $cat): ?>
        <option value="<?php echo $cat; ?>" <?php if (($user['category'] ?? '') == $cat) echo "selected";
 ?>>
            <?php echo $cat; ?>
        </option>
    <?php endforeach; ?>
</select>
<div class="error"><?php echo $error7; ?></div>

<button type="submit" name="update" class="btn">Update Profile</button><br><br>
<button type="button" class="btn" onclick="window.location.href='userprofile.php'">Go Back</button>

</form>

</div>
</div>

</body>
</html>
