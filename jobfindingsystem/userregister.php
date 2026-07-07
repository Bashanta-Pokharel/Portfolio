<?php
include "database.php";

/* ---------- Fetch Job Categories from Database ---------- */
$cat_result = mysqli_query($con, "SELECT * FROM job_categories ORDER BY name ASC");
$job_categories = [];

while ($cat_row = mysqli_fetch_assoc($cat_result)) {
    $job_categories[] = $cat_row['name'];
}

/* ---------- Variables ---------- */
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$fname = $lname = $username = $email = $qualification = $gender = $skills = '';

/* ---------- Form Submit ---------- */
if (isset($_POST["register"])) {

    $fname         = $_POST["fname"];
    $lname         = $_POST["lname"];
    $username      = $_POST["username"];
    $password      = $_POST["password"];
    $email         = $_POST["email"];
    $qualification = $_POST["qualification"];
    $gender        = $_POST["gender"] ?? '';
    $skills      = $_POST["skills"] ?? '';

    /* ---------- Validation ---------- */
    if (empty($fname))         $error1 = "*First name is required";
    if (empty($lname))         $error2 = "*Last name is required";
    if (empty($username))      $error3 = "*Username is required";
    if (empty($password))      $error4 = "*Password is required";
    if (empty($email))         $error5 = "*Email is required";
    if (empty($qualification)) $error6 = "*Qualification must be chosen";
    if (empty($gender))        $error8 = "*Please select your gender";
    if (empty($skills) || $skills == "all")
                               $error7 = "*Please select your job expertise";

    /* ---------- Check Username ---------- */
    if (!empty($username)) {
        $check_user = mysqli_query($con, "SELECT * FROM user WHERE username='$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error3 = "*Username already taken";
        }
    }

    /* ---------- Insert Data ---------- */
    if (
        empty($error1) && empty($error2) && empty($error3) &&
        empty($error4) && empty($error5) && empty($error6) &&
        empty($error7) && empty($error8)
    ) {

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO user 
                (fname, lname, username, password, email, qualification, skills, gender, datecreated)
                VALUES 
                ('$fname', '$lname', '$username', '$hashed_password', '$email', '$qualification', '$skills', '$gender', CURRENT_TIMESTAMP())";

        $res = mysqli_query($con, $sql);

        if ($res) {
            echo "<script>alert('Registration successful!'); window.location='index.php';</script>";
            exit;
        } else {
            echo "Registration failed: " . mysqli_error($con);
        }
    }
}
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

<div class="header">
    <h1>Online Job Finding System</h1>
    <h2>Welcome User Registration</h2>
</div>

<form method="post" class="login-form">
    <h1>Register</h1>

    <label class="form-label">First Name</label>
    <input type="text" name="fname" class="form-input" value="<?php echo $fname; ?>">
    <div class="error"><?php echo $error1; ?></div>

    <label class="form-label">Last Name</label>
    <input type="text" name="lname" class="form-input" value="<?php echo $lname; ?>">
    <div class="error"><?php echo $error2; ?></div>

    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-input" value="<?php echo $username; ?>">
    <div class="error"><?php echo $error3; ?></div>

    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-input">
    <div class="error"><?php echo $error4; ?></div>

    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-input" value="<?php echo $email; ?>">
    <div class="error"><?php echo $error5; ?></div>

    <label class="form-label">Gender</label>
    <div class="checkboxfont">
        <input type="radio" name="gender" value="Male"   <?php if ($gender=="Male") echo "checked"; ?>> Male
        <input type="radio" name="gender" value="Female" <?php if ($gender=="Female") echo "checked"; ?>> Female
        <input type="radio" name="gender" value="Other"  <?php if ($gender=="Other") echo "checked"; ?>> Other
    </div>
    <div class="error"><?php echo $error8; ?></div>

    <label class="form-label">Qualification</label>
    <select name="qualification" class="form-input">
        <option value="">-- Select Qualification --</option>
        <option value="High School" <?php if($qualification=="High School") echo "selected"; ?>>High School</option>
        <option value="+2" <?php if($qualification=="+2") echo "selected"; ?>>+2</option>
        <option value="Bachelor" <?php if($qualification=="Bachelor") echo "selected"; ?>>Bachelor Degree</option>
        <option value="Master" <?php if($qualification=="Master") echo "selected"; ?>>Master Degree</option>
        <option value="Other" <?php if($qualification=="Other") echo "selected"; ?>>Other</option>
    </select>
    <div class="error"><?php echo $error6; ?></div>

    <label class="form-label">Job Expertise</label>
    <select name="skills" class="form-input">
        <option value="all">-- Select Job Expertise --</option>
        <?php foreach($job_categories as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php if($skills == $cat) echo "selected"; ?>>
                <?php echo $cat; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="error"><?php echo $error7; ?></div>

    <button type="submit" name="register" class="btn">Register</button><br><br>

    <button type="button" class="btn" onclick="window.location.href='user.php'">
        Already have an account
    </button>
</form>

<div class="footer">
    <p style="font-size:1.9rem;text-align:center;">
        &copy;2025 Created by Bashanta and Kiran. Online Job Finding System.
    </p>
</div>

</body>
</html>
