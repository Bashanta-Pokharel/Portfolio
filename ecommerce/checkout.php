<?php
// Name: Bashanta Pokharel, Roll: 62(A)
include 'db.php';

// Copy cart products to orders table.
$cart = mysqli_query($conn, 'SELECT * FROM cart');
$stmt = mysqli_prepare($conn, 'INSERT INTO orders(product_id, name, price, image) VALUES(?, ?, ?, ?)');

while ($row = mysqli_fetch_assoc($cart)) {
    $qty = isset($row['quantity']) ? (int)$row['quantity'] : 1;
    for ($i = 0; $i < $qty; $i++) {
        mysqli_stmt_bind_param($stmt, 'isds', $row['product_id'], $row['name'], $row['price'], $row['image']);
        mysqli_stmt_execute($stmt);
    }
}

mysqli_query($conn, 'DELETE FROM cart');
header('Location: orders.php');
exit;
