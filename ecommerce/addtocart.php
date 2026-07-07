<?php
// Name: Bashanta Pokharel, Roll: 62(A)
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

// Check if product is already in the cart
$check_stmt = mysqli_prepare($conn, 'SELECT * FROM cart WHERE product_id = ?');
mysqli_stmt_bind_param($check_stmt, 'i', $id);
mysqli_stmt_execute($check_stmt);
$cart_item = mysqli_fetch_assoc(mysqli_stmt_get_result($check_stmt));

if ($cart_item) {
    // Increment quantity
    $update_stmt = mysqli_prepare($conn, 'UPDATE cart SET quantity = quantity + 1 WHERE product_id = ?');
    mysqli_stmt_bind_param($update_stmt, 'i', $id);
    mysqli_stmt_execute($update_stmt);
} else {
    // Insert new item with quantity = 1
    $insert_stmt = mysqli_prepare($conn, 'INSERT INTO cart(product_id, name, price, image, quantity) VALUES(?, ?, ?, ?, 1)');
    mysqli_stmt_bind_param($insert_stmt, 'isds', $id, $name, $price, $image);
    mysqli_stmt_execute($insert_stmt);
}

if (isset($_GET['buy'])) {
    header('Location: checkout.php');
} else {
    header('Location: cart.php');
}
exit;