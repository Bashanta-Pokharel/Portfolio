<?php

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "jfc",
    3336
);

if (!$con)
{
    die("Connection Failed: " . mysqli_connect_error());
}

?>