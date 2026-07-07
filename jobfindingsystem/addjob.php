<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>
            alert("Please loginc first");
            window.location.href = "index.php";
          </script>';
    exit;
}

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = '';
$title = $description = $location = $qualification = $salary = $expirydate = '';
$image = '';

if (isset($_POST["addjob"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $qualification = $_POST["qualification"];
    $salary = $_POST["salary"];
    $expirydate = $_POST["expirydate"];

    // Validation
    if (empty($title)) $error1 = "*Job title is required";
    if (empty($description)) $error2 = "*Description is required";
    if (empty($location)) $error3 = "*Location is required";
    if (empty($qualification)) $error4 = "*Qualification is required";
    if (empty($salary)) $error5 = "*Salary is required";
    if (empty($_FILES["file"]["name"])) $error6 = "*Job image is required";
    if (empty($expirydate)) $error7 = "*Expiry date is required";

    if (empty($error1) && empty($error2) && empty($error3) && empty($error4) && empty($error5) && empty($error6) && empty($error7)) {
        $imagedirectory = "images/";
        $image = basename($_FILES["file"]["name"]);
        $target_file = $imagedirectory . $image;

        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $sql = "INSERT INTO jobs (title, description, location, qualification, salary, image, expirydate)
                VALUES ('$title', '$description', '$location', '$qualification', '$salary', '$image', '$expirydate')";

        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Job added successfully!'); window.location='companyaddjobs.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error adding job: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job - Online Job Finding System</title>
    <!-- <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <div class="header">
        <h1>Online Job Finding System</h1>
        <h2>Add Job Post</h2>
    </div>

    <form method="post" class="login-form" enctype="multipart/form-data">
        <h1>Add New Job</h1>

        <label class="form-label">Job Title</label>
        <input type="text" name="title" placeholder="Enter job title" class="form-input" value="<?php echo $title; ?>">
        <div class="error"><?php echo $error1; ?></div>

        <label class="form-label">Description</label>
        <textarea name="description" placeholder="Enter job description" class="form-input" rows="4"><?php echo $description; ?></textarea>
        <div class="error"><?php echo $error2; ?></div>

        <label class="form-label">Location</label>
        <input type="text" name="location" placeholder="Enter job location" class="form-input" value="<?php echo $location; ?>">
        <div class="error"><?php echo $error3; ?></div>

        <label class="form-label">Qualification</label>
        <input type="text" name="qualification" placeholder="Enter qualification required" class="form-input" value="<?php echo $qualification; ?>">
        <div class="error"><?php echo $error4; ?></div>

        <label class="form-label">Salary</label>
        <input type="text" name="salary" placeholder="Enter salary range" class="form-input" value="<?php echo $salary; ?>">
        <div class="error"><?php echo $error5; ?></div>

        <label class="form-label">Upload Job Image</label>
        <input type="file" name="file" class="form-input">
        <div class="error"><?php echo $error6; ?></div>

        <button type="submit" name="addjob" class="btn">Add Job</button><br><br>
        <button type="button" class="btn" onclick="window.location.href='adminhomepage.php'">Go Back</button><br><br>
    </form>

    <div class="footer">
        <p style="font-size: 1.9rem; text-align: center;">
            &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
        </p>
    </div>
</body>
</html>
