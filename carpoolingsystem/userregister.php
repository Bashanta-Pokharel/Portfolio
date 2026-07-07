<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">🚗 Car Pooling System</div><div style="">user registration</div>
        <div>
            <a href="index.php">Home</a>
            <a href="riderlogin.php">Rider</a>
            <a href="userlogin.php">User Login</a>
        </div>
    </div>

    <div class="center-container">
        <div class="login-box">
            <h2>User Register</h2>

            <form action="#" method="POST">

                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-group">
                    <label>Phone</label>
                    <input type="text" name="phone" required>
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn">Register</button>
            </form>

            <p style="margin-top: 10px; font-size:14px;">
                Already have an account? <a href="userlogin.php" style="color:#00bcd4;">Login</a>
            </p>
        </div>
    </div>

</body>
</html>
