<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    echo "<script>
            alert('Please login first!');
            window.location.href = 'admin.php';
          </script>";
    exit;
}

if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];

    $check_sql = "SELECT * FROM user WHERE uid = $uid";
    $check_result = mysqli_query($con, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $deletesql = "DELETE FROM user WHERE uid = $uid";
        if (mysqli_query($con, $deletesql)) {
            echo "<script>
                    alert('User deleted successfully.');
                    window.location.href = 'adminviewuser.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error deleting user');
                    window.location.href = 'adminviewuser.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('User not found');
                window.location.href = 'adminviewuser.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'adminviewuser.php';
          </script>";
}
?>
