<?php
session_start();
if (!isset($_SESSION["username"])) {
    echo '<script>alert("Please login first"); window.location.href="index.php";</script>';
    exit;
}

include "database.php";

$success = $error = "";

/* ADD CATEGORY */
if (isset($_POST['add_category'])) {
    $category_name = $_POST['categoryname'];

    if ($category_name != "") {
        $check = mysqli_query($con, "SELECT * FROM job_categories WHERE name='$category_name'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Category already exists!";
        } else {
            mysqli_query($con, "INSERT INTO job_categories(name) VALUES('$category_name')");
            $success = "Category added successfully!";
        }
    } else {
        $error = "Category name cannot be empty!";
    }
}

$categories = mysqli_query($con, "SELECT * FROM job_categories ORDER BY id asc");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>JFS | Add Category</title>
<link rel="stylesheet" href="style3.css">
</head>

<body>

<!-- Header -->
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size:1.8em;">Admin Panel</div>
    <div class="loginbuttons">
        <a href="logout.php"><button>Logout</button></a>
    </div>
</div>

<!-- Sidebar -->
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
    <li><a href="addcategory.php" class="active">Add Category</a></li>
</ul>
</div>

<div class="main-content">
<div class="content">

<h2>Add Job Category</h2>

<?php if($success) echo "<p style='color:green;'>$success</p>"; ?>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

<!-- ADD FORM -->
<form method="post" style="margin-bottom:20px;">
    <label class="form-label">Category Name</label>
    <input type="text" name="categoryname" class="form-input" required>
    <input type="submit" name="add_category" value="Add Category" class="btn">
</form>

<h3>All Categories</h3>

<table class="custom-table">
<tr>
    <th>ID</th>
    <th>Category Name</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($categories)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td>
        <a href="admineditcategory.php?id=<?php echo $row['id']; ?>">
            <button class="action-btn edit-btn">Edit</button>
        </a>

        <a href="deletecategory.php?id=<?php echo $row['id']; ?>"
           onclick="return confirm('Delete this category?');">
            <button class="action-btn delete-btn">Delete</button>
        </a>
    </td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>
