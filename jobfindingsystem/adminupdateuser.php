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
            window.location.href = "adminviewuser.php";
          </script>';
    exit();
}

// Fetch job categories from database
$cat_result = mysqli_query($con, "SELECT name FROM job_categories ORDER BY name ASC");
$job_categories = [];
while ($row = mysqli_fetch_assoc($cat_result)) {
    $job_categories[] = $row['name'];
}

// Fetch user data
$sql = "SELECT * FROM user WHERE uid='$uid'";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo '<script>
            alert("User not found");
            window.location.href = "adminviewuser.php";
          </script>';
    exit();
}

$fname = $user['fname'];
$lname = $user['lname'];
$username = $user['username'];
$email = $user['email'];
$qualification = $user['qualification'];
$gender = $user['gender'];
$category = $user['category'] ?? '';

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';

if (isset($_POST["update"])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $qualification = $_POST["qualification"];
    $gender = $_POST["gender"] ?? '';
    $category = $_POST["category"] ?? '';

    // Validation
    if (empty($fname)) $error1 = "*First name is required";
    if (empty($lname)) $error2 = "*Last name is required";
    if (empty($username)) $error3 = "*Username is required";
    if (empty($email)) $error5 = "*Email is required";
    if (empty($qualification)) $error6 = "*Qualification is required";
    if (empty($category)) $error7 = "*Select job expertise";
    if (empty($gender)) $error8 = "*Select gender";

    // Check username uniqueness
    $check_user = mysqli_query($con, "SELECT * FROM user WHERE username='$username' AND uid != '$uid'");
    if ($check_user && mysqli_num_rows($check_user) > 0) {
        $error3 = "*Username already taken";
    }

    if (
        empty($error1) && empty($error2) && empty($error3) &&
        empty($error5) && empty($error6) && empty($error7) && empty($error8)
    ) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql_update = "UPDATE user SET
                fname='$fname',
                lname='$lname',
                username='$username',
                password='$hashed_password',
                email='$email',
                qualification='$qualification',
                gender='$gender',
                skills='$category'
                WHERE uid='$uid'";
        } else {
            $sql_update = "UPDATE user SET
                fname='$fname',
                lname='$lname',
                username='$username',
                email='$email',
                qualification='$qualification',
                gender='$gender',
                skills='$category'
                WHERE uid='$uid'";
        }

        if (mysqli_query($con, $sql_update)) {
            echo '<script>
                    alert("User updated successfully");
                    window.location.href="adminviewuser.php";
                  </script>';
            exit;
        } else {
            echo '<div class="error">Error: ' . mysqli_error($con) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Update User</title>
    <link rel="stylesheet" href="style3.css" />
</head>

<body>
    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>
        <div class="loginbuttons">
            <a href="logout.php"><button>Logout</button></a>
        </div>
    </div>

    <div class="sidebar">
       <ul class="nav-links">
            <li><a href="adminhomepage.php" >Home</a></li>
                <li><a href="adminviewcompany.php">view Companyes</a></li>

                <li><a href="adminviewuser.php">view Users</a></li>
                <li><a href="adminviewjobs.php"> view Jobs </a></li>
                <li><a href="adminviewpendingrequest.php">All pending Applications</a></li>
                <li><a href="allacceptedapplicant.php">All Accepted applicant </a></li>
                <li><a href="allrejectedapplicant.php">All Rejected applicant </a></li>

            
            <li><a href="adminupdateuser.php" class="active">Updating user</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content">
            <form method="post" class="login-form">
                <h1>Update User</h1>

                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-input" value="<?php echo htmlspecialchars($fname); ?>" />
                <div class="error"><?php echo $error1; ?></div>

                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-input" value="<?php echo htmlspecialchars($lname); ?>" />
                <div class="error"><?php echo $error2; ?></div>

                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($username); ?>" />
                <div class="error"><?php echo $error3; ?></div>

                <label class="form-label">Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="form-input" />
                <div class="error"><?php echo $error4; ?></div>

                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" />
                <div class="error"><?php echo $error5; ?></div>

                <label class="form-label">Gender</label>
                <div class="checkboxfont">
                    <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?> /> Male
                    <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?> /> Female
                    <input type="radio" name="gender" value="Other" <?php if ($gender == "Other") echo "checked"; ?> /> Other
                </div>
                <div class="error"><?php echo $error8; ?></div>

                <label class="form-label">Qualification</label>
                <select name="qualification" class="form-input">
                    <option value="">-- Select Qualification --</option>
                    <option value="High School" <?php if ($qualification == "High School") echo "selected"; ?>>High School</option>
                    <option value="+2" <?php if ($qualification == "+2") echo "selected"; ?>>+2</option>
                    <option value="Bachelor" <?php if ($qualification == "Bachelor") echo "selected"; ?>>Bachelor Degree</option>
                    <option value="Master" <?php if ($qualification == "Master") echo "selected"; ?>>Master Degree</option>
                    <option value="Other" <?php if ($qualification == "Other") echo "selected"; ?>>Other</option>
                </select>
                <div class="error"><?php echo $error6; ?></div>

                <label class="form-label">Job Expertise</label>
                <select name="category" class="form-input">
                    <option value="">-- Select Job Expertise --</option>
                    <?php foreach ($job_categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php if ($category == $cat) echo "selected"; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="error"><?php echo $error7; ?></div>

                <br />
                <button type="submit" name="update" class="btn">Update User</button><br><br>
                <button type="button" class="btn" onclick="window.location.href='adminviewuser.php'">Go Back</button>
            </form>
        </div>
    </div>
</body>

</html>
