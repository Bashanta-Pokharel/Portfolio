<?php
// Name: Bashanta Pokharel, Roll: 62(A)
// Helper functions for the e-commerce system.

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function db_column_exists($conn, $table, $column) {
    $table = mysqli_real_escape_string($conn, $table);
    $column = mysqli_real_escape_string($conn, $column);
    $result = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");

    return $result && mysqli_num_rows($result) > 0;
}

function db_add_column_if_missing($conn, $table, $column, $definition) {
    if (!db_column_exists($conn, $table, $column)) {
        mysqli_query($conn, "ALTER TABLE `$table` ADD `$column` $definition");
    }
}

function ensure_product_schema($conn) {
    db_add_column_if_missing($conn, 'products', 'name', 'VARCHAR(100)');
    db_add_column_if_missing($conn, 'products', 'product_name', 'VARCHAR(100)');
    db_add_column_if_missing($conn, 'products', 'description', 'TEXT');
    db_add_column_if_missing($conn, 'products', 'category', 'VARCHAR(100)');
    db_add_column_if_missing($conn, 'products', 'price', 'DECIMAL(10,2)');
    db_add_column_if_missing($conn, 'products', 'stock', 'INT DEFAULT 0');
    db_add_column_if_missing($conn, 'products', 'image', 'VARCHAR(255)');
    db_add_column_if_missing($conn, 'products', 'created_at', 'DATE');

    // Cart table updates
    db_add_column_if_missing($conn, 'cart', 'quantity', 'INT NOT NULL DEFAULT 1');

    mysqli_query($conn, "UPDATE products SET product_name = name WHERE (product_name IS NULL OR product_name = '') AND name IS NOT NULL AND name != ''");
    mysqli_query($conn, "UPDATE products SET name = product_name WHERE (name IS NULL OR name = '') AND product_name IS NOT NULL AND product_name != ''");
    mysqli_query($conn, "UPDATE products SET created_at = CURDATE() WHERE created_at IS NULL");
}

function product_name($product) {
    if (!empty($product['product_name'])) {
        return $product['product_name'];
    }

    return $product['name'] ?? '';
}

function product_image_src($image) {
    if (empty($image)) {
        return 'images/placeholder.svg';
    }

    if (strpos($image, 'images/') === 0 || strpos($image, 'uploads/') === 0) {
        return $image;
    }

    return 'images/' . $image;
}

function upload_product_image($field, &$error) {
    if (empty($_FILES[$field]['name'])) {
        $error = '* Product image is required';
        return '';
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        $error = '* Image upload failed';
        return '';
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $type = mime_content_type($_FILES[$field]['tmp_name']);

    if (!in_array($type, $allowedTypes, true)) {
        $error = '* Upload JPG, PNG, GIF, or WebP image only';
        return '';
    }

    $extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $destination = __DIR__ . '/images/' . $filename;

    if (!is_dir(__DIR__ . '/images')) {
        mkdir(__DIR__ . '/images', 0777, true);
    }

    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
        $error = '* Could not save uploaded image';
        return '';
    }

    return $filename;
}

// Call schema initialization using the connection
global $conn;
if (isset($conn)) {
    ensure_product_schema($conn);
}
