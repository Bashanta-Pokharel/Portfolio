<?php
include "db.php";
include "helpers.php";

$cat_result = mysqli_query($con, "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$product_categories = [];
while ($cat_row = mysqli_fetch_assoc($cat_result)) {
    $product_categories[] = $cat_row['category'];
}

$selectedCategory = $_POST['category'] ?? 'all';

if (isset($_POST['Filter']) && $selectedCategory !== 'all') {
    $stmt = mysqli_prepare($con, "SELECT * FROM products WHERE category = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 's', $selectedCategory);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $selectedCategory = 'all';
    $result = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - E-Commerce System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="headerlogin">
    <h1>E-Commerce</h1>
    <h2>System</h2>
</div>

<div class="navbar">
    <div class="nav-container">
        <a href="index.php"><div class="nav-item">Home</div></a>
        <a href="products.php"><div class="nav-item" style="background-color:#e8f0fe; color:#1a73e8;">Products</div></a>
        <a href="cart.php"><div class="nav-item">Cart</div></a>
        <a href="wishlist.php"><div class="nav-item">Wishlist</div></a>
        <a href="orders.php"><div class="nav-item">Orders</div></a>
        <a href="admin.php"><div class="nav-item">Admin</div></a>
    </div>
</div>

<div class="filter-container">
    <form method="post">
        <label for="category"><strong>Select Product Category:</strong></label>
        <select name="category" id="category">
            <option value="all">-- All Categories --</option>
            <?php foreach ($product_categories as $cat): ?>
                <option value="<?php echo e($cat); ?>" <?php if ($selectedCategory === $cat) echo 'selected'; ?>><?php echo e($cat); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="Filter" value="Filter" class="btn">
    </form>
</div>

<div class="products">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e(product_name($row)); ?>">
                <h3><?php echo e(product_name($row)); ?></h3>
                <p><strong>Description:</strong> <?php echo e($row['description']); ?></p>
                <p><strong>Category:</strong> <?php echo e($row['category']); ?></p>
                <p><strong>Price:</strong> Rs. <?php echo e($row['price']); ?></p>
                <p><strong>Stock:</strong> <?php echo e($row['stock']); ?></p>
                <p><strong>Added On:</strong> <?php echo e(date('d-m-Y', strtotime($row['created_at']))); ?></p>
                <div class="action-row">
                    <a class="btn" href="product_view.php?id=<?php echo e($row['id']); ?>">View</a>
                    <a class="btn" href="addtocart.php?id=<?php echo e($row['id']); ?>">Add to Cart</a>
                    <a class="btn buy" href="addtocart.php?id=<?php echo e($row['id']); ?>&buy=1">Buy Now</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="font-size:1.5rem; text-align:center;">No products available in this category.</p>
    <?php endif; ?>
</div>

<div class="footer">
    <p style="font-size:1.2rem; text-align:center;">&copy; 2025 Created by Bashanta Pokharel. E-Commerce System. All Rights Reserved.</p>
</div>
</body>
</html>