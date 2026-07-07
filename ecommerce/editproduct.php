<?php
include "db.php";
include "helpers.php";

$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($con, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header('Location: addproduct.php');
    exit;
}

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = '';
$product_name = product_name($row);
$description = $row['description'];
$category = $row['category'];
$price = $row['price'];
$stock = $row['stock'];
$image = $row['image'];

if (isset($_POST['updateproduct'])) {
    $product_name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    if ($product_name === '') {
        $error1 = '* Product Name Required';
    }

    if ($description === '') {
        $error2 = '* Description Required';
    }

    if ($category === '') {
        $error3 = '* Category Required';
    }

    if ($price === '') {
        $error4 = '* Price Required';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error4 = '* Enter valid price';
    }

    if ($stock === '') {
        $error5 = '* Stock Required';
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error5 = '* Enter valid stock quantity';
    }

    if (!empty($_FILES['image']['name'])) {
        $newImage = upload_product_image('image', $error6);
        if ($error6 === '') {
            $image = $newImage;
        }
    }

    if ($error1 === '' && $error2 === '' && $error3 === '' && $error4 === '' && $error5 === '' && $error6 === '') {
        $stmt = mysqli_prepare($con, "UPDATE products SET name = ?, product_name = ?, description = ?, category = ?, price = ?, stock = ?, image = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'ssssdisi', $product_name, $product_name, $description, $category, $price, $stock, $image, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Product Updated Successfully'); window.location='addproduct.php';</script>";
            exit;
        }

        echo "<script>alert('Update Failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="headerlogin">
    <h1>E-Commerce</h1>
    <h2>Edit Product</h2>
</div>

<div class="container">
    <h2 style="text-align:center;">Update Product</h2>
    <form method="post" enctype="multipart/form-data" class="login-form">
        <div class="form-group">
            <label>Product Name</label>
            <div class="field-col">
                <div class="input-container">
                    <input type="text" name="product_name" class="form-input <?php if ($error1 !== '') echo 'has-error'; ?>" value="<?php echo e($product_name); ?>">
                    <?php if ($error1 !== ''): ?>
                        <div class="error"><?php echo e($error1); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <div class="field-col">
                <div class="input-container">
                    <textarea name="description" rows="4" class="form-input <?php if ($error2 !== '') echo 'has-error'; ?>"><?php echo e($description); ?></textarea>
                    <?php if ($error2 !== ''): ?>
                        <div class="error"><?php echo e($error2); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Category</label>
            <div class="field-col">
                <div class="input-container">
                    <input type="text" name="category" class="form-input <?php if ($error3 !== '') echo 'has-error'; ?>" value="<?php echo e($category); ?>">
                    <?php if ($error3 !== ''): ?>
                        <div class="error"><?php echo e($error3); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Price</label>
            <div class="field-col">
                <div class="input-container">
                    <input type="number" step="0.01" name="price" class="form-input <?php if ($error4 !== '') echo 'has-error'; ?>" value="<?php echo e($price); ?>">
                    <?php if ($error4 !== ''): ?>
                        <div class="error"><?php echo e($error4); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <div class="field-col">
                <div class="input-container">
                    <input type="number" name="stock" class="form-input <?php if ($error5 !== '') echo 'has-error'; ?>" value="<?php echo e($stock); ?>">
                    <?php if ($error5 !== ''): ?>
                        <div class="error"><?php echo e($error5); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Current Image</label>
            <div class="field-col">
                <img src="<?php echo e(product_image_src($image)); ?>" width="150" style="margin-bottom:10px;">
            </div>
        </div>

        <div class="form-group">
            <label>Change Image (Optional)</label>
            <div class="field-col">
                <div class="input-container">
                    <input type="file" name="image" class="form-input <?php if ($error6 !== '') echo 'has-error'; ?>" accept="image/*">
                    <?php if ($error6 !== ''): ?>
                        <div class="error"><?php echo e($error6); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div style="width: 160px; flex-shrink: 0;"></div>
            <div class="field-col">
                <button type="submit" name="updateproduct" class="btn" style="margin: 0;">Update Product</button>
                <a href="addproduct.php" class="btn" style="margin: 0 0 0 10px;">Back</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>