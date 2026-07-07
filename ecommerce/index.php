<?php
include 'db.php';
include 'helpers.php';
$products = mysqli_query($conn, 'SELECT * FROM products ORDER BY id DESC');
?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Commerce</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Simple E-Commerce System</h1>
<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="addproduct.php">Add Product</a>
    <a href="cart.php">Cart</a>
    <a href="wishlist.php">Wishlist</a>
    <a href="orders.php">Orders</a>
</nav>
<div class="grid">
<?php while ($row = mysqli_fetch_assoc($products)) { ?>
    <div class="card">
        <img src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e(product_name($row)); ?>">
        <h3><?php echo e(product_name($row)); ?></h3>
        <p>Rs. <?php echo e($row['price']); ?></p>
        <a class="btn" href="product_view.php?id=<?php echo e($row['id']); ?>">View</a>
        <a class="btn" href="addtocart.php?id=<?php echo e($row['id']); ?>">Add to Cart</a>
        <a class="btn wish" href="addtowishlist.php?id=<?php echo e($row['id']); ?>">Add to Wishlist</a>
        <a class="btn buy" href="addtocart.php?id=<?php echo e($row['id']); ?>&buy=1">Buy Now</a>
    </div>
<?php } ?>
</div>
</body>
</html>