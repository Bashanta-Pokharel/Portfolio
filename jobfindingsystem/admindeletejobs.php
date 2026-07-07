<?php
session_start();
include "database.php"; 
if (!isset($_SESSION["username"])) {
    echo "<script>
            alert('Please login first!');
            window.location.href = 'index.php';
          </script>";
    exit;
}
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $check_sql = "SELECT * FROM jobs WHERE id = $job_id";
    $check_result = mysqli_query($con, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $delete_sql = "DELETE FROM jobs WHERE id = $job_id";
        if (mysqli_query($con, $delete_sql)) {
            echo "<script>
                    alert('Job deleted successfully.');
                    window.location.href = 'adminviewjobs.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error deleting job: " . mysqli_error($con) . "');
                    window.location.href = 'adminviewjobs.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Job not found.');
                window.location.href = 'adminviewjobs.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'adminviewjobs.php';
          </script>";
}
?>
