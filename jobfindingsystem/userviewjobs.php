<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; // DB connection

    // Fetch job categories from database
    $cat_result = mysqli_query($con, "SELECT * FROM job_categories ORDER BY name ASC");
    $job_categories = [];
    while ($cat_row = mysqli_fetch_assoc($cat_result)) {
        $job_categories[] = $cat_row['name'];
    }

    // Initialize category filter
    $selectedCategory = '';
    if (isset($_POST['Filter'])) {
        $selectedCategory = $_POST['category'];
        if ($selectedCategory != 'all') {
            $sql = "SELECT * FROM jobs WHERE category='$selectedCategory' ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM jobs ORDER BY id DESC";
        }
    } else {
        $sql = "SELECT * FROM jobs ORDER BY id DESC";
    }
    $result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JFS</title>
<link rel="stylesheet" href="style3.css">
</head>
<body>

<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.8em;">User Panel</div>
    <div class="loginbuttons">
        <a href="userprofile.php" style="color: white; text-decoration: none;"><button>Profile</button></a>
        <a href="logout.php" style="color: white; text-decoration: none;"><button>Logout</button></a>
    </div>
</div>

<div class="sidebar">
    <ul class="nav-links">
        <li><a href="userhomepage.php">Home</a></li>
        <li><a href="userviewjobs.php" class="active">View Jobs</a></li>
        <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
        <li><a href="useraccepted.php">View Accepted Jobs</a></li>
        <li><a href="userrejected.php">View Rejected Jobs</a></li>
        <li><a href="userprofile.php">Profile</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content">
        <!-- Filter Section -->
        <div class="filter-container">
            <form method="post">
                <label for="category"><strong>Select Job Category:</strong></label>
                <select name="category" id="category">
                    <option value="all">-- All Categories --</option>
                    <?php foreach ($job_categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php if ($selectedCategory == $cat) echo "selected"; ?>>
                            <?php echo $cat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" name="Filter" value="Filter" class="submitbtn">
            </form>
        </div>

        <!-- Jobs Display -->
        <div class="jobs">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <div class='job-card'>
                        <img src='images/{$row['image']}' alt='{$row['title']}'>
                        <h3>{$row['title']}</h3>
                        <p><strong>Description:</strong> {$row['description']}</p>
                        <p><strong>Location:</strong> {$row['location']}</p>
                        <p><strong>Qualification:</strong> {$row['qualification']}</p>
                        <p><strong>Salary:</strong> {$row['salary']}</p>
                        <p><strong>Category:</strong> {$row['category']}</p>
                        <p><strong>Posted On:</strong> " . date("d-m-Y", strtotime($row['openeddate'])) . "</p>
                        <p><strong>Expiry Date:</strong> " . date("d-m-Y", strtotime($row['expirydate'])) . "</p>
                        <button class='btn' onclick=\"window.location.href='applyjob.php?job_id={$row['id']}'\">Apply</button>
                    </div>
                    ";
                }
            } else {
                echo "<p style='font-size:1.5rem;text-align:center;'>No jobs available in this category.</p>";
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
