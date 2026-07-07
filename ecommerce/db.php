<?php
// Shared MySQL connection file.
$conn = mysqli_connect('localhost:3336', 'root', '', 'ecommerce');

if (!$conn) {
    die('Database connection failed');
}

// Some pages used $conn and others used $con. Keep both working.
$con = $conn;