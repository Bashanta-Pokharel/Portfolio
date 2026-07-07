<?php
include 'db.php';

mysqli_query($conn, 'DELETE FROM cart');

header('Location: cart.php');
exit;
