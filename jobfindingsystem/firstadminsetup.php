<?php
include "database.php"; // make sure this connects to your DB

$error1 = $error2 = $error3 = "";
$username = $password = "";

if (isset($_POST["register"])) {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validation
    if (empty($username)) {
        $error1 = "*Username is required";
    }
    if (empty($password)) {
        $error2 = "*Password is required";
    }

    // Check if username exists
    if (!empty($username)) {
        $check_user = mysqli_query($con, "SELECT * FROM admin WHERE username = '$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error1 = "*Username already exists";
        }
    }

    // If no errors, insert into DB
    if (empty($error1) && empty($error2)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";
        $res = mysqli_query($con, $sql);

        if ($res) {
            echo "<script>alert('Admin registered successfully!'); window.location='adminlogin.php';</script>";
            exit;
        } else {
            $error3 = "Database error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="header">
        <h1>Online Job Finding System</h1>
        <h2>Admin Registration</h2>
    </div>

    <form method="post" class="login-form">
        <h1>Register Admin</h1>

        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" placeholder="Enter username" class="form-input" value="<?php echo $username; ?>">
        <div class="error"><?php echo $error1; ?></div>

        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" placeholder="Enter password" class="form-input">
        <div class="error"><?php echo $error2; ?></div>

        <div class="error"><?php echo $error3; ?></div>

        <button type="submit" name="register" class="btn">Register</button><br><br>
        <button type="button" class="btn" onclick="window.location.href='adminlogin.php'">Already have an account</button>
    </form>

    <div class="footer">
        <p style="font-size: 1.9rem; text-align: center;">
            &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
        </p>
    </div>
</body>
</html>
