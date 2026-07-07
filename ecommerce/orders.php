<?php
include 'db.php';
include 'helpers.php';
$orders = mysqli_query($conn, 'SELECT * FROM orders ORDER BY id DESC');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Ordered Products</h1>
<nav><a href="index.php">Home</a> <a href="products.php">Products</a> <a href="cart.php">Cart</a></nav>
<table>
    <tr><th>Image</th><th>Name</th><th>Price</th><th>Date</th></tr>
    <?php while ($row = mysqli_fetch_assoc($orders)) { ?>
    <tr>
        <td><img class="small" src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e($row['name']); ?>"></td>
        <td><?php echo e($row['name']); ?></td>
        <td>Rs. <?php echo e($row['price']); ?></td>
        <td><?php echo e($row['order_date']); ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>