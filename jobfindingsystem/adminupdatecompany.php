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

$cid = $_GET["cid"] ?? '';

if ($cid == '') {
    echo '<script>
            alert("Invalid Company ID");
            window.location.href = "adminviewcompany.php";
          </script>';
    exit();
}
$sql = "SELECT * FROM company WHERE cid='$cid'";
$result = mysqli_query($con, $sql);
$company = mysqli_fetch_assoc($result);
$cname = $company['company_name'];
$username = $company['username'];
$email = $company['email'];
$address = $company['address'];
$pan = $company['company_pan'];
$license = $company['company_license'];
$category = $company['company_type'];
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$errordb = '';
if (isset($_POST["update"])) {
    $cname = $_POST["cname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $address = $_POST["address"] ?? '';
    $pan = $_POST["pan"];
    $license = $_POST["license"];
    $category = $_POST["category"];
    // Check uniqueness
    $check_user = mysqli_query($con, "SELECT * FROM company WHERE username = '$username' AND cid != '$cid'");
    $check_pan = mysqli_query($con, "SELECT * FROM company WHERE company_pan = '$pan' AND cid != '$cid'");
    $check_license = mysqli_query($con, "SELECT * FROM company WHERE company_license = '$license' AND cid != '$cid'");

    if ($check_user && mysqli_num_rows($check_user) > 0) $error2 = "*Username already taken";
    if ($check_pan && mysqli_num_rows($check_pan) > 0) $error5 = "*Company PAN already exists";
    if ($check_license && mysqli_num_rows($check_license) > 0) $error6 = "*Company License already exists";
    if (empty($cname)) $error1 = "*Company Name is required";
    if (empty($username)) $error2 = "*Username is required";
    if (empty($email)) $error4 = "*Email is required";
    if (empty($address)) $error8 = "*Address is required";
    if (empty($pan)) $error5 = "*Company PAN is required";
    if (empty($license)) $error6 = "*Company License Number is required";
    if (empty($category)) $error7 = "*Please select Company Category";
    if (empty($error1) && empty($error2) && empty($error3) && empty($error4) &&
        empty($error5) && empty($error6) && empty($error7) && empty($error8)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE company 
                    SET company_name='$cname', username='$username', password='$hashed_password',
                        email='$email', address='$address', company_pan='$pan', 
                        company_license='$license', company_type='$category'
                    WHERE cid='$cid'";
        } else {
            $sql = "UPDATE company 
                    SET company_name='$cname', username='$username',
                        email='$email', address='$address', company_pan='$pan', 
                        company_license='$license', company_type='$category'
                    WHERE cid='$cid'";
        }

        $res = mysqli_query($con, $sql);

        if ($res) {
            echo '<script>
                    alert("Company updated successfully");
                    window.location.href="adminviewcompany.php";
                  </script>';
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
     <link rel="stylesheet" href="style3.css">
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
            <li><a href="adminupdatecompany.php" class="active">Editing Company</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content">
            <h1>updating company Company</h1>
            <form method="POST"class="login-form">
                <label class="form-label">Company Name</label>
                <input class="form-input" type="text" name="cname" value="<?php echo $cname; ?>" required>
                <div class="error"><?php echo $error1; ?></div>

                <label class="form-label">Username</label>
                <input class="form-input" type="text" name="username" value="<?php echo $username; ?>" required>
                <div class="error"><?php echo $error2; ?></div>

                <label class="form-label">Password (Leave blank to keep old)</label>
                <input class="form-input" type="password" name="password">
                <div class="error"><?php echo $error3; ?></div>

                <label class="form-label">Email</label>
                <input class="form-input" type="email" name="email" value="<?php echo $email; ?>" required>
                <div class="error"><?php echo $error4; ?></div>

                <label class="form-label">Address</label>
                <input class="form-input" type="text" name="address" value="<?php echo $address; ?>" required>
                <div class="error"><?php echo $error8; ?></div>

                <label class="form-label">Company PAN</label>
                <input class="form-input" type="text" name="pan" value="<?php echo $pan; ?>" required>
                <div class="error"><?php echo $error5; ?></div>

                <label class="form-label">Company License</label>
                <input class="form-input" type="text" name="license" value="<?php echo $license; ?>" required>
                <div class="error"><?php echo $error6; ?></div>

                <label class="form-label">Category</label>
                <select class="form-input" name="category" required>
                    <option value="">--Select--</option>
                    <option value="Manufacturing" <?php if($category=="Manufacturing") echo "selected"; ?>>Manufacturing</option>
                    <option value="IT" <?php if($category=="IT") echo "selected"; ?>>IT</option>
                    <option value="Service" <?php if($category=="Service") echo "selected"; ?>>Service</option>
                    <option value="Other" <?php if($category=="Other") echo "selected"; ?>>Other</option>
                </select>
                <div class="error"><?php echo $error7; ?></div><br>

                <button type="submit" name="update" class="btn">Update Company</button><br><br>
                <button class="btn"><a href="adminviewcompany.php" style="color:white;">Go Back</a></button>
            </form>
        </div>
    </div>
</body>

</html>
