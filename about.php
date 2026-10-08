<?php
require_once 'config.php'; // DB connection + helper functions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoevinir - About Us</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200">

<!-- NAVBAR -->
<nav class="fixed w-full z-50 bg-black bg-opacity-80 backdrop-blur-lg">
    <div class="container mx-auto flex justify-between items-center py-4 px-4">
        <a href="index.php" class="text-violet-500 text-2xl font-bold">Shoe<span class="text-white">vinir</span></a>
        <button class="mobile-menu-btn text-white text-2xl md:hidden">☰</button>
        <ul class="nav-links hidden md:flex gap-8 items-center">
            <li><a href="index.php" class="hover:text-violet-500">Home</a></li>
            <li><a href="products.php" class="hover:text-violet-500">Products</a></li>
            <li><a href="about.php" class="hover:text-violet-500 font-semibold">About</a></li>
            <li><a href="contact.php" class="hover:text-violet-500">Contact</a></li>
            <li class="relative dropdown">
                <span class="dropdown-toggle cursor-pointer hover:text-violet-500">Account</span>
                <div class="dropdown-content absolute left-0 mt-2 bg-gray-800 rounded-lg shadow-lg p-2 hidden">
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-violet-500 hover:text-white rounded">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="block px-4 py-2 hover:bg-violet-500 hover:text-white rounded">Login</a>
                        <a href="register.php" class="block px-4 py-2 hover:bg-violet-500 hover:text-white rounded">Register</a>
                    <?php endif; ?>
                </div>
            </li>
            <li>
                <a href="cart.php" class="cart-icon relative text-white text-xl">
                    🛒 <span class="cart-badge absolute -top-2 -right-2 bg-violet-500 text-white text-xs px-2 py-1 rounded-full">0</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- PAGE HEADER -->
<header class="page-header text-center py-32 bg-gradient-to-br from-black via-gray-900 to-gray-800">
    <h1 class="text-5xl font-bold text-violet-500 mb-4">About Us</h1>
    <p class="text-gray-400 text-lg">Learn more about Shoevinir and our mission</p>
</header>

<!-- ABOUT SECTION -->
<section class="section py-16">
    <div class="container mx-auto grid md:grid-cols-2 gap-12">
        <!-- Left Column: Story + Features -->
        <div>
            <h2 class="text-3xl font-bold text-violet-500 mb-4">Our Story</h2>
            <p class="text-gray-300 mb-4">
                Founded in 2020, Shoevinir started with a simple mission: to make premium footwear accessible to everyone.
                What began as a small online store has grown into a trusted destination for shoe enthusiasts worldwide.
            </p>
            <p class="text-gray-300 mb-4">
                We carefully curate our product selection, partnering with leading brands to bring you the best in sneakers and accessories.
                Every product in our store undergoes rigorous quality testing to ensure it meets our high standards.
            </p>
            <h2 class="text-3xl font-bold text-violet-500 mb-4">Why Choose Us</h2>
            <ul class="list-disc list-inside text-gray-300 space-y-2">
                <li>Carefully curated premium products</li>
                <li>Competitive prices with regular deals</li>
                <li>Free shipping on orders over $50</li>
                <li>30-day hassle-free returns</li>
                <li>24/7 customer support</li>
                <li>Secure payment processing</li>
            </ul>
        </div>

        <!-- Right Column: Mission + Numbers -->
        <div>
            <h2 class="text-3xl font-bold text-violet-500 mb-4">Our Mission</h2>
            <p class="text-gray-300 mb-6">
                We believe that great shoes should be available to everyone. Our mission is to provide high-quality products at fair prices, backed by exceptional customer service.
            </p>
            <h2 class="text-3xl font-bold text-violet-500 mb-4">By The Numbers</h2>
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-800 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-violet-500">10K+</div>
                    <p class="text-gray-400">Happy Customers</p>
                </div>
                <div class="bg-gray-800 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-violet-500">500+</div>
                    <p class="text-gray-400">Products</p>
                </div>
                <div class="bg-gray-800 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-violet-500">50+</div>
                    <p class="text-gray-400">Brand Partners</p>
                </div>
                <div class="bg-gray-800 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-violet-500">4.9★</div>
                    <p class="text-gray-400">Average Rating</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer bg-black bg-opacity-80 py-8">
    <div class="container text-center text-gray-400">
        <p>&copy; 2026 Shoevinir. All rights reserved.</p>
    </div>
</footer>

<!-- Optional JS for mobile dropdown -->
<script>
document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
        const menu = toggle.nextElementSibling;
        menu.classList.toggle('hidden');
    });
});
</script>

</body>
</html>