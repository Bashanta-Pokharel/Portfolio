<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">🚗 Car Pooling System</div>
        <div>
            <a href="index.php">Home</a>
            <a href="riderlogin.php">Rider</a>
            <a href="userlogin.php">User</a>
        </div>
    </div>

    <!-- LOGIN BOX CENTER -->
    <div class="center-container">
        <div class="login-box">
            <h2>Rider Login</h2>

            <form action="#" method="POST">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn">Login</button><br>
                <a href="riderregister.php">Register for rider</a>
            </form>
        </div>
    </div>

</body>
</html>
