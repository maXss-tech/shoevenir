<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shoevinir - Best Shoes Store</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container">

        <a href="index.php" class="logo">shoe<span>vinir</span></a>

        

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>

            <li class="dropdown">
                <span class="dropdown-toggle">Categories</span>
                <div class="dropdown-content">
                    <a href="products.php?category=electronics">Electronics</a>
                    <a href="products.php?category=accessories">Accessories</a>
                    <a href="products.php?category=gadgets">Gadgets</a>
                </div>
            </li>

            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>

            <!-- ACCOUNT DROPDOWN -->
            <li class="dropdown">
                <span class="dropdown-toggle">Account</span>
                <div class="dropdown-content">
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    <?php endif; ?>
                    <a href="admin-login.php">Admin Login</a>
                </div>
            </li>

            <li>
                <a href="cart.php" class="cart-icon">
                    🛒 <span class="cart-badge">0</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">

    <div class="hero-content">

        <h1>Welcome to Shoevinir</h1>

        <?php if (isAdmin()): ?>
<div style="margin:20px 0;">
    <a href="dashboard.php" class="btn btn-primary">
        🛠 Go Back to Dashboard
    </a>
</div>
<?php endif; ?>

        <p>
            Discover premium sneakers and stylish shoes
            with unbeatable quality and comfort.
        </p>

        <div class="hero-buttons">

            <a href="products.php" class="btn btn-primary">
                Shop Now
            </a>

            <a href="about.php" class="btn btn-secondary">
                Learn More
            </a>

        </div>

    </div>

</section>

<!-- ================= FEATURED PRODUCTS ================= -->
<section class="section">

    <div class="container">

        <h2 class="section-title">
            Featured Products
        </h2>

        <div class="products-grid">

            <!-- PRODUCT 1 -->
            <div class="product-card">

                <img 
                    src="https://i.postimg.cc/G3xwgKpx/1.jpg"
                    class="product-image"
                >

                <div class="product-info">

                    <span class="product-category">
                        Sneakers
                    </span>

                    <h3 class="product-name">
                        Nike AirMax 97
                    </h3>

                    <p class="product-description">
                        Premium streetwear sneakers with
                        modern comfort and style.
                    </p>

                    <p class="product-price">
                        $37
                    </p>

                    <button class="btn btn-primary">
                        Add to Cart
                    </button>

                </div>

            </div>

            <!-- PRODUCT 2 -->
            <div class="product-card">

                <img 
                    src="https://i.postimg.cc/rmpx6QkW/2.jpg"
                    class="product-image"
                >

                <div class="product-info">

                    <span class="product-category">
                        Basketball
                    </span>

                    <h3 class="product-name">
                        Air Jordan 14 Ferrari
                    </h3>

                    <p class="product-description">
                        Stylish basketball shoes with
                        premium performance design.
                    </p>

                    <p class="product-price">
                        $215
                    </p>

                    <button class="btn btn-primary">
                        Add to Cart
                    </button>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= WHY CHOOSE US ================= -->
<section class="section">

    <div class="container">

        <h2 class="section-title">
            Why Choose Shoevinir
        </h2>

        <div class="products-grid">

            <!-- FEATURE 1 -->
            <div class="product-card">

                <div class="product-info text-center">

                    <div style="font-size: 3rem; margin-bottom: 15px;">
                        🚚
                    </div>

                    <h3>
                        Fast Shipping
                    </h3>

                    <p>
                        Quick nationwide delivery
                        for all orders.
                    </p>

                </div>

            </div>

            <!-- FEATURE 2 -->
            <div class="product-card">

                <div class="product-info text-center">

                    <div style="font-size: 3rem; margin-bottom: 15px;">
                        🔒
                    </div>

                    <h3>
                        Secure Payments
                    </h3>

                    <p>
                        Safe and protected checkout
                        system for customers.
                    </p>

                </div>

            </div>

            <!-- FEATURE 3 -->
            <div class="product-card">

                <div class="product-info text-center">

                    <div style="font-size: 3rem; margin-bottom: 15px;">
                        ⭐
                    </div>

                    <h3>
                        Premium Quality
                    </h3>

                    <p>
                        Authentic and high-quality
                        sneakers collection.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">

    <div class="container">

        <div class="footer-grid">

            <!-- FOOTER LEFT -->
            <div class="footer-section">

                <h3>
                    Shoevinir
                </h3>

                <p>
                    Premium sneakers and streetwear
                    for modern fashion lovers.
                </p>

            </div>

            <!-- FOOTER LINKS -->
            <div class="footer-section">

                <h3>
                    Quick Links
                </h3>

                <ul>

                    <li>
                        <a href="index.php">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="products.php">
                            Products
                        </a>
                    </li>

                    <li>
                        <a href="about.php">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="contact.php">
                            Contact
                        </a>
                    </li>

                </ul>

            </div>

        </div>

        <!-- COPYRIGHT -->
        <div class="footer-bottom">

            <p>
                &copy; 2026 Shoevinir.
                All rights reserved.
            </p>

        </div>

    </div>

</footer>


<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>