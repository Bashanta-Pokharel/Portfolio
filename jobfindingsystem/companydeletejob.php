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

$username = $_SESSION["username"];

if (isset($_GET['id'])) {
    $jobid = intval($_GET['id']); 

   
    $sql = "DELETE FROM jobs WHERE id = $jobid AND username = '$username'";

    if (mysqli_query($con, $sql)) {
        echo "<script>
                alert('Job deleted successfully!');
                window.location.href='companyhomepage.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Error deleting job: ');
                window.location.href='companyhomepage.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request!');
            window.location.href='companyhomepage.php';
          </script>";
}
?>
