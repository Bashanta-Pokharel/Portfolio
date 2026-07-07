<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
    exit();
}

// Get user ID from URL
$uid = $_GET["uid"] ?? '';

if ($uid == '') {
    echo '<script>
            alert("Invalid User ID");
            window.location.href = "userprofile.php";
          </script>';
    exit();
}

// Fetch user data
$sql = "SELECT * FROM user WHERE uid='$uid'";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

$fname = $user['fname'];
$lname = $user['lname'];
$username = $user['username'];
$email = $user['email'];
$qualification = $user['qualification'];
$gender = $user['gender'];
$skills = explode(", ", $user['skills']); // convert string to array

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$errordb = '';

if (isset($_POST["update"])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $qualification = $_POST["qualification"];
    $gender = $_POST["gender"] ?? '';
    $skills = isset($_POST["skills"]) ? $_POST["skills"] : [];

    // Check uniqueness
    $check_user = mysqli_query($con, "SELECT * FROM user WHERE username='$username' AND uid != '$uid'");
    if ($check_user && mysqli_num_rows($check_user) > 0)
        $error3 = "*Username already taken";

    // Validation
    if (empty($fname))
        $error1 = "*First name is required";
    if (empty($lname))
        $error2 = "*Last name is required";
    if (empty($username))
        $error3 = "*Username is required";
    if (empty($email))
        $error5 = "*Email is required";
    if (empty($qualification))
        $error6 = "*Qualification must be chosen";
    if (empty($skills))
        $error7 = "*Please select at least one skill";
    if (empty($gender))
        $error8 = "*Please select your gender";

    if (
        empty($error1) && empty($error2) && empty($error3) && empty($error4) && empty($error5) &&
        empty($error6) && empty($error7) && empty($error8)
    ) {

        $skills_str = implode(", ", $skills);

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE user 
                    SET fname='$fname', lname='$lname', username='$username', password='$hashed_password',
                        email='$email', qualification='$qualification', gender='$gender', skills='$skills_str'
                    WHERE uid='$uid'";
        } else {
            $sql = "UPDATE user 
                    SET fname='$fname', lname='$lname', username='$username',
                        email='$email', qualification='$qualification', gender='$gender', skills='$skills_str'
                    WHERE uid='$uid'";
        }

        $res = mysqli_query($con, $sql);

        if ($res) {
            echo '<script>
                    alert("User updated successfully");
                    window.location.href="userprofile.php";
                  </script>';
            exit;
        } else {
            $errordb = "Update failed: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Company</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
        <link rel="stylesheet" href="style3.css">
</head>

<body>
    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.5em;">User Panel</div>
        <div class="loginbuttons">
            <a href="logout.php"><button>Logout</button></a>
        </div>
    </div>

     <div class="sidebar">
            <ul class="nav-links">
                <li><a href="userhomepage.php" >Home</a></li>
                <li><a href="userviewjobs.php">View Jobs</a></li>
                <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
                <li><a href="userrejected.php" >View Rejected Jobs</a></li>
                <li><a href="userprofile.php" >Profile</a></li>
                <li><a href="userprofile.php" class="active">Updating Profile</a></li>
            </ul>
        </div>


    <div class="main-content">
        <div class="content">
            <form method="post" class="login-form">
                <h1>Updating profile</h1>

                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-input" value="<?php echo $fname; ?>">
                <div class="error"><?php echo $error1; ?></div>

                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-input" value="<?php echo $lname; ?>">
                <div class="error"><?php echo $error2; ?></div>

                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" value="<?php echo $username; ?>">
                <div class="error"><?php echo $error3; ?></div>

                <label class="form-label">Password (Leave blank to keep old)</label>
                <input type="password" name="password" class="form-input">
                <div class="error"><?php echo $error4; ?></div>

                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="<?php echo $email; ?>">
                <div class="error"><?php echo $error5; ?></div>

                <label class="form-label">Gender</label>
                <div class="checkboxfont">
                    <input type="radio" name="gender" value="Male" <?php if ($gender == "Male")
                        echo "checked"; ?>> Male
                    <input type="radio" name="gender" value="Female" <?php if ($gender == "Female")
                        echo "checked"; ?>>
                    Female
                    <input type="radio" name="gender" value="Other" <?php if ($gender == "Other")
                        echo "checked"; ?>>
                    Other
                </div>
                <div class="error"><?php echo $error8; ?></div>

                <label class="form-label">Qualification</label>
                <select name="qualification" class="form-input">
                    <option value="">-- Select Qualification --</option>
                    <option value="High School" <?php if ($qualification == "High School")
                        echo "selected"; ?>>High School
                    </option>
                    <option value="+2" <?php if ($qualification == "+2")
                        echo "selected"; ?>>+2</option>
                    <option value="Bachelor" <?php if ($qualification == "Bachelor")
                        echo "selected"; ?>>Bachelor Degree
                    </option>
                    <option value="Master" <?php if ($qualification == "Master")
                        echo "selected"; ?>>Master Degree</option>
                    <option value="Other" <?php if ($qualification == "Other")
                        echo "selected"; ?>>Other</option>
                </select>
                <div class="error"><?php echo $error6; ?></div>

                <label class="form-label">Skills</label>
                <div class="checkboxfont">
                    <?php
                    $all_skills = ["HTML", "CSS", "JavaScript", "PHP", "MySQL", "Python", "Java", "C++", "Other"];
                    foreach ($all_skills as $skill) {
                        $checked = in_array($skill, $skills) ? "checked" : "";
                        echo "<input type='checkbox' name='skills[]' value='$skill' $checked> $skill ";
                    }
                    ?>
                </div>
                <div class="error"><?php echo $error7; ?></div><br>

                <button type="submit" name="update" class="btn">Update User</button><br><br>
                <button class="btn"><a href="userprofile.php" style="color:white; text-decoration:none;">Go
                        Back</a></button>
            </form>
        </div>
    </div>
</body>

</html>