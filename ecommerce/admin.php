<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Header -->
    <div class="headerlogin">
        <h1>E-Commerce</h1>
        <h2>System</h2>
    </div>

    <!-- Navigation -->
    <div class="navbar">
        <div class="nav-container">

            <a href="index.php">
                <div class="nav-item">Home</div>
            </a>

            <a href="products.php">
                <div class="nav-item">Products</div>
            </a>

            <a href="cart.php">
                <div class="nav-item">Cart</div>
            </a>

            <a href="wishlist.php">
                <div class="nav-item">Wishlist</div>
            </a>

            <a href="orders.php">
                <div class="nav-item">Orders</div>
            </a>

            <div class="nav-item"
                style="background-color:#e8f0fe; color:#1a73e8;">
                Admin
            </div>

        </div>
    </div>

    <!-- Welcome Section -->
    <div class="welcome-section">
        <h2>Admin Dashboard</h2>
        <p>Manage products in the E-Commerce System.</p>
    </div>

    <!-- Admin Options -->
    <div class="products">

        <div class="product-card">
            <h3>Add New Product</h3>
            <p>Create and add new products to the store.</p>
            <a href="addproduct.php" class="btn">
                Add Product
            </a>
        </div>

        <div class="product-card">
            <h3>Edit Product</h3>
            <p>Update existing product details.</p>
            <a href="editproduct.php" class="btn">
                Edit Product
            </a>
        </div>

        <div class="product-card">
            <h3>Delete Product</h3>
            <p>Remove products from the store.</p>
            <a href="deleteproduct.php" class="btn">
                Delete Product
            </a>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p style="font-size:1.2rem; text-align:center;">
            &copy; 2025 Created by Bashanta and Kiran.
            E-Commerce System. All Rights Reserved.
        </p>
    </div>

</body>

</html>