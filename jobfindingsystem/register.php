<?php
include "database.php";
$error1 = $error2 = $error3 = $error4 = $error5 = '';
$fname = $lname = $username = $email = '';
if (isset($_POST["register"])) {

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $check_user = mysqli_query($con, "SELECT * FROM register WHERE username = '$username'");
    if (mysqli_num_rows($check_user) > 0) {
        $error3 = "*Username already taken";
    }
    if (empty($fname)) $error1 = "*First name is required";
    if (empty($lname)) $error2 = "*Last name is required";
    if (empty($username)) $error3 = "*Username is required";
    if (empty($password)) $error4 = "*Password is required";
    if (empty($email)) $error5 = "*Email is required";
    if (empty($error1) && empty($error2) && empty($error3) && empty($error4) && empty($error5)) 
        {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO register (fname, lname, username, password, email) 
                VALUES ('$fname', '$lname', '$username', '$hashed_password', '$email')";
        $res = mysqli_query($con,$sql);

        
        if($res) {
            echo "<script>alert('Registration successful!'); window.location='index.php';</script>";
        } else {
            $errordb = "Registration failed: ";
        }
    }
}
?>
<?php 

?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="style3.css"> 
</head>
<body>


    <div class="box">
    <h2>Register</h2>
    

    <?php if (isset($errordb)): ?>
        <div class="error"><?php echo $errordb; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <label for="">Fname</label>
        <input type="text" name="fname" placeholder="First Name" value="<?php echo $fname; ?>">
        <div class="error"><?php echo $error1; ?></div>

        <label for="">Lname</label>
        <input type="text" name="lname" placeholder="Last Name" value="<?php echo $lname; ?>">
        <div class="error"><?php echo $error2; ?></div>

        <label for="">Username</label>
        <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
        <div class="error"><?php echo $error3; ?></div>

        <label for="">Password</label>
        <input type="password" name="password" placeholder="Password">
        <div class="error"><?php echo $error4; ?></div>

        <label for="">Email</label>
        <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
        <div class="error"><?php echo $error5; ?></div>
        
        <button type="submit" name="register">Register</button>
    </form>
    
    <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</body>
</html>