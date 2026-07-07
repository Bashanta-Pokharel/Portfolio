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
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo e(product_name($product)); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?php echo e(product_name($product)); ?></h1>
<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
    <a href="wishlist.php">Wishlist</a>
</nav>

<div class="product-view">
    <div>
        <img src="<?php echo e(product_image_src($product['image'])); ?>" alt="<?php echo e(product_name($product)); ?>">
    </div>
    <div class="product-info">
        <h2><?php echo e(product_name($product)); ?></h2>
        <p class="price">Rs. <?php echo e($product['price']); ?></p>
        <p><?php echo nl2br(e($product['description'])); ?></p>
        <p><strong>Category:</strong> <?php echo e($product['category']); ?></p>
        <p><strong>Stock:</strong> <?php echo e($product['stock']); ?></p>
        <p><strong>Added On:</strong> <?php echo e(date('d-m-Y', strtotime($product['created_at']))); ?></p>
        <a class="btn" href="addtocart.php?id=<?php echo e($product['id']); ?>">Add to Cart</a>
        <a class="btn wish" href="addtowishlist.php?id=<?php echo e($product['id']); ?>">Add to Wishlist</a>
        <a class="btn buy" href="addtocart.php?id=<?php echo e($product['id']); ?>&buy=1">Buy Now</a>
    </div>
</div>
</body>
</html>