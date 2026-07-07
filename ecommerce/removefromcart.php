<?php
// Name: Bashanta Pokharel, Roll: 62(A)
include 'db.php';
include 'helpers.php';

$id = (int) ($_GET['id'] ?? 0);
$removeAll = (int) ($_GET['remove_all'] ?? 0);

if ($id > 0) {
    if ($removeAll) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM cart WHERE product_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    } else {
        // Check current quantity
        $stmt = mysqli_prepare($conn, 'SELECT quantity FROM cart WHERE product_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $qty = (int)$row['quantity'];
            if ($qty > 1) {
                $stmt = mysqli_prepare($conn, 'UPDATE cart SET quantity = quantity - 1 WHERE product_id = ?');
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
            } else {
                $stmt = mysqli_prepare($conn, 'DELETE FROM cart WHERE product_id = ?');
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}

header('Location: cart.php');
exit;
