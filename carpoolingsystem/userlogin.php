<?php
session_start();
include "database.php"; // Your database connection file

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
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) { // assuming password hashed
                $_SESSION['username'] = $username;
                header("Location: userhomepage.php");
                exit();
            } else {
                $error2 = "*Invalid password";
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
<title>User Login</title>
<link rel="stylesheet" href="style.css">
<style>
.error { color: red; font-size: 13px; margin-top: 3px; }
</style>
</head>
<body>

<div class="navbar">
    <div class="logo">🚗 Car Pooling System</div>
    <div>
        <a href="index.php">Home</a>
        <a href="riderlogin.php">Rider</a>
        <a href="userlogin.php">User</a>
    </div>
</div>

<div class="center-container">
    <div class="login-box">
        <h2>User Login</h2>

        <form action="" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <?php if($error1) echo '<div class="error">'.$error1.'</div>'; ?>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password">
                <?php if($error2) echo '<div class="error">'.$error2.'</div>'; ?>
            </div>

            <button type="submit" name="login" class="btn">Login</button><br>
            <a href="userregister.php">Register for user</a>
        </form>
    </div>
</div>

</body>
</html>
