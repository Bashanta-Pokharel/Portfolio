<?php
// Name: Bashanta Pokharel, Roll: 62(A)
include 'db.php';
include 'helpers.php';
$total = 0;
$cart = mysqli_query($conn, 'SELECT * FROM cart');
$cart_items = [];
while ($row = mysqli_fetch_assoc($cart)) {
    $cart_items[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="student-banner">
    Name: Bashanta Pokharel | Roll: 62(A)
</div>

<h1>Shopping Cart</h1>
<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <?php if (!empty($cart_items)): ?>
        <a href="emptycart.php">Empty Cart</a>
        <a href="checkout.php">Checkout</a>
    <?php endif; ?>
</nav>

<div class="container">
    <?php if (empty($cart_items)): ?>
        <p style="text-align: center; font-size: 1.2rem; margin: 40px 0;">Your shopping cart is empty.</p>
        <p style="text-align: center;"><a href="products.php" class="btn">Shop Products</a></p>
    <?php else: ?>
        <table style="width: 100%; max-width: 900px; margin: 20px auto; background: white; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 6px; overflow: hidden;">
            <thead>
                <tr style="background-color: #1f7a5c; color: white;">
                    <th style="padding: 12px; border: none;">Image</th>
                    <th style="padding: 12px; border: none;">Product Name</th>
                    <th style="padding: 12px; border: none;">Unit Price</th>
                    <th style="padding: 12px; border: none;">Quantity</th>
                    <th style="padding: 12px; border: none;">Subtotal</th>
                    <th style="padding: 12px; border: none;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cart_items as $row): 
                $qty = isset($row['quantity']) ? (int)$row['quantity'] : 1;
                $subtotal = $row['price'] * $qty;
                $total += $subtotal;
            ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 12px; vertical-align: middle;">
                        <img class="small" src="<?php echo e(product_image_src($row['image'])); ?>" alt="<?php echo e($row['name']); ?>" style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px; background: #fafafa;">
                    </td>
                    <td style="padding: 12px; vertical-align: middle; font-weight: bold;">
                        <a href="product_view.php?id=<?php echo e($row['product_id']); ?>" style="color: #1f7a5c; text-decoration: none;">
                            <?php echo e($row['name']); ?>
                        </a>
                    </td>
                    <td style="padding: 12px; vertical-align: middle;">Rs. <?php echo e(number_format($row['price'], 2)); ?></td>
                    <td style="padding: 12px; vertical-align: middle;">
                        <div class="qty-controls" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <a href="removefromcart.php?id=<?php echo e($row['product_id']); ?>" class="btn-qty btn-minus" style="font-weight: bold; padding: 2px 8px; font-size: 1rem;">-</a>
                            <span style="font-weight: bold; font-size: 1.1rem; min-width: 20px; text-align: center; display: inline-block;"><?php echo e($qty); ?></span>
                            <a href="addtocart.php?id=<?php echo e($row['product_id']); ?>" class="btn-qty" style="font-weight: bold; padding: 2px 6px; font-size: 1rem;">+</a>
                        </div>
                    </td>
                    <td style="padding: 12px; vertical-align: middle; font-weight: bold;">Rs. <?php echo e(number_format($subtotal, 2)); ?></td>
                    <td style="padding: 12px; vertical-align: middle;">
                        <a href="removefromcart.php?id=<?php echo e($row['product_id']); ?>&remove_all=1" class="btn" style="background: #b00020; margin: 0; padding: 6px 12px; font-size: 0.9rem;" onclick="return confirm('Remove this product from cart?')">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h2 style="text-align: center; margin-top: 20px;">Total Amount: <span style="color: #1f7a5c;">Rs. <?php echo e(number_format($total, 2)); ?></span></h2>
    <?php endif; ?>
</div>
</body>
</html>