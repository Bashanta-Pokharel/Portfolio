<?php
include "database.php";
$cid = $_GET['cid'];

mysqli_query($con, "UPDATE company SET status='rejected' WHERE cid='$cid'");
header("Location: adminviewcompanyrequest.php");
?>
