<?php
session_start();   

include "database.php";

$error1 = $error2 = '';
$username = '';
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (empty($username)) {
        $error1 = "*Username is required";
    }
    if (empty($password)) {
        $error2 = "*Password is required";
    }

    if (empty($error1) && empty($error2)) {
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location:userhomepage.php");
                exit();
            } else {
                $error2 = " *Invalid password";
            }
        } else {
            $error1 = "*Username not found";
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
     <div class="headerlogin">
        <h1>Online Job Finding System</h1>
        <h2>System</h2>
        
    </div>

    <div class="navbar">
        <div class="nav-container">
            <a href="index.php">
                <div class="nav-item">Home</div>
            </a>
            <a href="jobs.php">
                <div class="nav-item">Jobs</div>
            </a>
            <a href="admin.php">
                <div class="nav-item" >Admin</div>
            </a>
            <a href="user.php">
                <div class="nav-item" style="background-color: #e8f0fe;
            color: #1a73e8">user</div>
            </a>
            <a href="company.php">
                <div class="nav-item">Company</div>
            </a>
        </div>
    </div>

    
    <form action="" method="post" class="login-form">
   
        <h1>Login</h1>
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" placeholder="Username" class="form-input"
            value="<?php echo htmlspecialchars($username); ?>">
        <div class="error"><?php echo $error1; ?></div>

        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-input">
        <div class="error"><?php echo $error2; ?></div>

        <button type="submit" name="login" class="btn">Login</button>
       <p style="font-size: 28px; text-align: center;">
    <a href="userregister.php">Don't have an account? Register here</a>
</p>

    </form>



    <div class="footer">
        <p style="font-size: 1.9rem; text-align: center;">
  &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
</p>

    </div>
</body>

</html>