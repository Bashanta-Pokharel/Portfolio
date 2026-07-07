<?php
session_start();
include "database.php"; 

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

 
    $jobQuery = "SELECT * FROM jobs WHERE username='$username' ORDER BY id DESC";
    $result = mysqli_query($con, $jobQuery);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JFS</title>
    <!-- <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="style3.css">
</head>

<body>

    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.5em;">Company Panel</div>
        <div class="loginbuttons">
            <button>Profile</button>
            <a href="logout.php" style="color: white; text-decoration: none;">
                <button>Logout</button>
            </a>
        </div>
    </div>

    <div class="sidebar">
        <ul class="nav-links">
            <li><a href="companyhomepage.php" class="active">Home</a></li>
            <li><a href="companyaddjobs.php">Add Jobs</a></li>
            <li><a href="companyrecivedrequest.php">Received Requests</a></li>    
            <li><a href="companyaccepteduser.php">Accepted User</a></li>     
                <li><a href="companydeclinerequest.php">Declined User</a></li>       
            <li><a href="companyprofile.php">Profile</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="content">
            <h2 style="text-align:center; margin-bottom:20px;">My Applied Jobs</h2>

            <div class="jobs">
                <?php
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                            <div class='job-card'>
                                <img src='images/" . $row['image'] . "' alt='" . $row['title'] . "'>
                                <h3>" . $row['title'] . "</h3>
                                <p><strong>Description:</strong> " . $row['description'] . "</p>
                                <p><strong>Location:</strong> " . $row['location'] . "</p>
                                <p><strong>Qualification:</strong> " . $row['qualification'] . "</p>
                                <p><strong>Salary:</strong> " . $row['salary'] . "</p>
                                <p><strong>Posted On:</strong> " . date("d-m-Y", strtotime($row['openeddate'])) . "</p>
                                <p><strong>Expiry Date:</strong> " . date("d-m-Y", strtotime($row['expirydate'])) . "</p>
                                <a href='companydeletejob.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this job?');\">
                                <button class='btn' style='background-color: red; color: white;'>Delete</button>

                                </a>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p style='font-size:1.5rem;text-align:center;'>No jobs available right now.</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Error fetching jobs: " . mysqli_error($con) . "</p>";
                }
                ?>

                
            </div>
        </div>
    </div>


</body>

</html>
<?php
} else {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
}
?>
