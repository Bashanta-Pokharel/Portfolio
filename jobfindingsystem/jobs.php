<?php
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
<title>Available Jobs - Online Job Finding System</title>
<link rel="stylesheet" href="style3.css">
</head>
<body>
<div class="headerlogin">
    <h1>Online Job Finding System</h1>
    <h2>System</h2>
</div>
<div class="navbar">
    <div class="nav-container">
        <a href="index.php"><div class="nav-item">Home</div></a>
        <a href="jobs.php"><div class="nav-item" style="background-color: #e8f0fe; color: #1a73e8">Jobs</div></a>
        <a href="admin.php"><div class="nav-item">Admin</div></a>
        <a href="user.php"><div class="nav-item">User</div></a>
        <a href="company.php"><div class="nav-item">Company</div></a>
    </div>
</div>

<!-- Category Filter -->
<div class="filter-container">
    <form method="post">
        <label for="category"><strong>Select Job Category:</strong></label>
        <select name="category" id="category">
            <option value="all">-- All Categories --</option>
            <?php foreach($job_categories as $cat): ?>
                <option value="<?php echo $cat; ?>" <?php if($selectedCategory==$cat) echo "selected"; ?>>
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
                <p><strong>Responsibilities:</strong> {$row['description']}</p>
                <p><strong>Location:</strong> {$row['location']}</p>
                <p><strong>Qualification:</strong> {$row['qualification']}</p>
                <p><strong>Salary:</strong> {$row['salary']}</p>
                <p><strong>Category:</strong> {$row['category']}</p>
                <p><strong>Posted On:</strong> " . date("d-m-Y", strtotime($row['openeddate'])) . "</p>
                <p><strong>Expiry Date:</strong> " . date("d-m-Y", strtotime($row['expirydate'])) . "</p>
                <button class='btn' onclick=\"alert('Please login first'); window.location.href='userregister.php';\">Apply</button>
            </div>
            ";
        }
    } else {
        echo "<p style='font-size:1.5rem;text-align:center;'>No jobs available in this category.</p>";
    }
    ?>
</div>

<div class="footer">
    <p style="font-size: 1.9rem; text-align: center;">
        &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
    </p>
</div>

</body>
</html>
