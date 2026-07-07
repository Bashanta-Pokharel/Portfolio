<?php
include 'db.php';
include 'helpers.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, 'SELECT * FROM products WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$product) {
    header('Location: products.php');
    exit;
}

$name = product_name($product);
$price = $product['price'];
$image = $product['image'];
$stmt = mysqli_prepare($conn, 'INSERT INTO wishlist(product_id, name, price, image) VALUES(?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'isds', $id, $name, $price, $image);
mysqli_stmt_execute($stmt);

header('Location: wishlist.php');
exit;