<?php
include 'db.php';
include 'helpers.php';
$wishlist = mysqli_query($conn, 'SELECT * FROM wishlist');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wishlist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Wishlist</h1>
<nav><a href="index.php">Home</a> <a href="products.php">Products</a> <a href="cart.php">Cart</a></nav>
<div class="grid">
<?php while ($row = mysqli_fetch_assoc($wishlist)) { ?>
    <div class="card">
        <img src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e($row['name']); ?>">
        <h3><?php echo e($row['name']); ?></h3>
        <p>Rs. <?php echo e($row['price']); ?></p>
        <a class="btn" href="product_view.php?id=<?php echo e($row['product_id']); ?>">View Product</a>
        <a class="btn" href="addtocart.php?id=<?php echo e($row['product_id']); ?>">Add to Cart</a>
    </div>
<?php } ?>
</div>
</body>
</html>