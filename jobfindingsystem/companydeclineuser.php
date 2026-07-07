<?php
session_start();
include "database.php"; // DB connection

if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('Please login first!');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$username = $_SESSION['username'];

// ✅ Step 1: Get the current company's cid using the session username
$cid = null;
$getCompanyQuery = "SELECT cid FROM company WHERE username = '$username' LIMIT 1";
$getCompanyResult = mysqli_query($con, $getCompanyQuery);

if ($getCompanyResult && mysqli_num_rows($getCompanyResult) === 1) {
    $companyData = mysqli_fetch_assoc($getCompanyResult);
    $cid = $companyData['cid'];
} else {
    echo "<script>
            alert('Company not found.');
            window.location.href = 'companyrecivedrequest.php';
          </script>";
    exit;
}

// ✅ Step 2: Process decline request
if (isset($_POST['decline'])) {
    $application_id = $_POST['application_id'] ?? '';
    $job_id = $_POST['job_id'] ?? '';

    if (empty($application_id) || empty($job_id)) {
        echo "<script>
                alert('Invalid request data!');
                window.location.href = 'companyrecivedrequest.php';
              </script>";
        exit;
    }

    // Check if already declined
    $check_sql = "SELECT * FROM declined_application WHERE application_id = '$application_id'";
    $check_result = mysqli_query($con, $check_sql);
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('This application has already been declined.');
                window.location.href = 'companyrecivedrequest.php';
              </script>";
        exit;
    }

    // Fetch the job application info
    $app_sql = "SELECT * FROM jobapplication WHERE id = '$application_id' AND job_id = '$job_id'";
    $app_result = mysqli_query($con, $app_sql);

    if ($app_result && mysqli_num_rows($app_result) > 0) {
        $application = mysqli_fetch_assoc($app_result);

        $app_username = $application['username'] ?? '';
        $fullname     = $application['fullname'];
        $email        = $application['email'];
        $phone        = $application['phone'];
        $address      = $application['address'];
        $skills       = $application['skills'];
        $experiences  = $application['experiences'];
        $photo        = $application['photo'];
        $cv           = $application['cv'];

        // ✅ Insert into declined_application table
        $insert_sql = "INSERT INTO declined_application 
            (application_id, job_id, fullname, email, phone, address, skills, experiences, photo, cv, rejected_at, username, cid)
            VALUES 
            ('$application_id', '$job_id', '$fullname', '$email', '$phone', '$address', '$skills', '$experiences', '$photo', '$cv', NOW(), '$app_username', '$cid')";

        if (mysqli_query($con, $insert_sql)) {
            // ✅ Remove from jobapplication table
            $delete_sql = "DELETE FROM jobapplication WHERE id = '$application_id'";
            if (mysqli_query($con, $delete_sql)) {
                echo "<script>
                        alert('Application declined successfully.');
                        window.location.href = 'companyrecivedrequest.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Declined, but failed to remove from pending list.');
                        window.location.href = 'companyrecivedrequest.php';
                      </script>";
            }
        } else {
            $err = mysqli_error($con);
            echo "<script>
                    alert('Failed to insert into declined list: $err');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Application not found.');
                window.location.href = 'companyrecivedrequest.php';
              </script>";
    }
} else {
    header("Location: companyrecivedrequest.php");
    exit;
}
?>
