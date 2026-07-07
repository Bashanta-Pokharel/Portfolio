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

// Get ID
$cid = $_GET["id"] ?? '';

if ($cid == '') {
    echo '<script>
            alert("Invalid Category ID");
            window.location.href = "addcategory.php";
          </script>';
    exit();
}

// Fetch category
$q = mysqli_query($con, "SELECT * FROM job_categories WHERE id='$cid'");
$cat = mysqli_fetch_assoc($q);

if (!$cat) {
    echo '<script>
            alert("Category not found");
            window.location.href = "addcategory.php";
          </script>';
    exit();
}

$name = $cat['name'];
$error = '';

if (isset($_POST["update"])) {
    $name = trim($_POST["category_name"]);

    if (empty($name)) {
        $error = "*Category name is required";
    }

    if (empty($error)) {
        mysqli_query($con, "UPDATE job_categories SET name='$name' WHERE id='$cid'");

        echo '<script>
                alert("Category updated successfully");
                window.location.href="addcategory.php";
              </script>';
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Category</title>
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
        <li><a href="adminhomepage.php">Home</a></li>
    <li><a href="adminviewcompany.php">View Companies</a></li>
    <li><a href="adminviewcompanyrequest.php">Company Requests</a></li>
    <li><a href="adminviewuser.php">View Users</a></li>
    <li><a href="adminviewjobs.php">View Jobs</a></li>
    <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
    <li><a href="allacceptedapplicant.php">Accepted Applicant</a></li>
    <li><a href="allrejectedapplicant.php">Declined Applicant</a></li>
    <li><a href="addcategory.php">Add Category</a></li>
    <li><a href="add_category.php" class="active">Add Category</a></li>
    </ul>
</div>

<div class="main-content">
<div class="content">

<form method="post" class="login-form">
    <h1>Update Category</h1>

    <label class="form-label">Category Name</label>
    <input type="text" name="category_name" class="form-input" value="<?php echo $name; ?>">
    <div class="error"><?php echo $error; ?></div>

    <button type="submit" name="update" class="btn">Update</button><br><br>

    <button class="btn">
        <a href="addcategory.php" style="color:white; text-decoration:none;">Go Back</a>
    </button>
</form>

</div>
</div>

</body>
</html>
