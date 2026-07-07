<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
    exit;
}

if (isset($_GET['cid'])) {
    $cid = $_GET['cid']; 
    $check_sql = "SELECT * FROM company WHERE cid = $cid";
    $check_result = mysqli_query($con, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {

        $delete_appli = "DELETE FROM jobapplication WHERE job_id IN (SELECT id FROM jobs WHERE company_id = $cid)";
        mysqli_query($con, $delete_appli);

        $delete_jobs = "DELETE FROM jobs WHERE company_id = $cid";
        mysqli_query($con, $delete_jobs);

        $delete_sql = "DELETE FROM company WHERE cid = $cid";
        if (mysqli_query($con, $delete_sql)) {
            echo '<script>
                    alert("Company and related data deleted successfully!");
                    window.location.href = "adminviewcompany.php";
                  </script>';
        } else {
            echo '<script>
                    alert("Error deleting company. Please try again.");
                    window.location.href = "adminviewcompany.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Company not found!");
                window.location.href = "adminviewcompany.php";
              </script>';
    }
} else {
    echo '<script>
            alert("Invalid request!");
            window.location.href = "adminviewcompany.php";
          </script>';
}
?>
