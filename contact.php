<?php
// ============================================
// CONTACT PAGE PHP
// Beginner Friendly Version
// ============================================

// Success or error message
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data safely
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $userMessage = htmlspecialchars($_POST['message']);

    // Example only (No database yet)
    // You can save this into database later

    $message = "Message sent successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TechShop</title>

    <!-- CSS FILE -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <div class="container">

            <!-- LOGO -->
            <a href="index.php" class="logo">
                Tech<span>Shop</span>
            </a>

            <!-- MOBILE BUTTON -->
            

            <!-- NAV LINKS -->
            <ul class="nav-links">

                <li><a href="index.php">Home</a></li>

                <li><a href="products.php">Products</a></li>

                <!-- DROPDOWN -->
                <li class="dropdown">

                    <span class="dropdown-toggle">
                        Categories
                    </span>

                    <div class="dropdown-content">

                        <a href="products.php?category=electronics">
                            Electronics
                        </a>

                        <a href="products.php?category=accessories">
                            Accessories
                        </a>

                    </div>
                </li>

                <li><a href="about.php">About</a></li>

                <li><a href="contact.php">Contact</a></li>

                <!-- ACCOUNT DROPDOWN -->
                <li class="dropdown">

                    <span class="dropdown-toggle">
                        Account
                    </span>

                    <div class="dropdown-content">

                        <a href="login.php">
                            Login
                        </a>

                        <a href="register.php">
                            Register
                        </a>

                    </div>
                </li>

                <!-- CART -->
                <li>
                    <a href="cart.php" class="cart-icon">
                        🛒
                        <span class="cart-badge">0</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    <!-- ================= PAGE HEADER ================= -->
    <header class="page-header">

        <h1>Contact Us</h1>

        <p>We'd love to hear from you</p>

    </header>

    <!-- ================= CONTACT SECTION ================= -->
    <section class="section">

        <div class="container">

            <div class="contact-content">

                <!-- ================= CONTACT FORM ================= -->
                <div class="form-container" style="margin:0; max-width:none;">

                    <h2 class="form-title">
                        Send us a Message
                    </h2>

                    <!-- SUCCESS MESSAGE -->
                    <?php if($message != ""): ?>

                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>

                    <?php endif; ?>

                    <!-- FORM -->
                    <form action="" method="POST">

                        <!-- NAME -->
                        <div class="form-group">

                            <label for="name">
                                Your Name
                            </label>

                            <input 
                                type="text"
                                id="name"
                                name="name"
                                required
                                placeholder="John Doe"
                            >

                        </div>

                        <!-- EMAIL -->
                        <div class="form-group">

                            <label for="email">
                                Email Address
                            </label>

                            <input 
                                type="email"
                                id="email"
                                name="email"
                                required
                                placeholder="john@example.com"
                            >

                        </div>

                        <!-- SUBJECT -->
                        <div class="form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <input 
                                type="text"
                                id="subject"
                                name="subject"
                                required
                                placeholder="How can we help?"
                            >

                        </div>

                        <!-- MESSAGE -->
                        <div class="form-group">

                            <label for="message">
                                Message
                            </label>

                            <textarea 
                                id="message"
                                name="message"
                                required
                                placeholder="Write your message here..."
                            ></textarea>

                        </div>

                        <!-- BUTTON -->
                        <button 
                            type="submit"
                            class="btn btn-primary form-btn"
                        >
                            Send Message
                        </button>

                    </form>

                </div>

                <!-- ================= CONTACT INFO ================= -->
                <div class="contact-info">

                    <h2>Get in Touch</h2>

                    <p class="text-muted mb-20">
                        Have questions? We're here to help!
                    </p>

                    <!-- EMAIL -->
                    <div class="contact-info-item">

                        <div class="contact-icon">
                            📧
                        </div>

                        <div>
                            <h4>Email</h4>

                            <p class="text-muted">
                                info@techshop.com
                            </p>
                        </div>

                    </div>

                    <!-- PHONE -->
                    <div class="contact-info-item">

                        <div class="contact-icon">
                            📞
                        </div>

                        <div>
                            <h4>Phone</h4>

                            <p class="text-muted">
                                +1 234 567 890
                            </p>
                        </div>

                    </div>

                    <!-- ADDRESS -->
                    <div class="contact-info-item">

                        <div class="contact-icon">
                            📍
                        </div>

                        <div>
                            <h4>Address</h4>

                            <p class="text-muted">
                                123 Tech Street, New York
                            </p>
                        </div>

                    </div>

                    <!-- HOURS -->
                    <div class="contact-info-item">

                        <div class="contact-icon">
                            🕐
                        </div>

                        <div>
                            <h4>Business Hours</h4>

                            <p class="text-muted">
                                Mon - Fri: 9AM - 6PM
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="footer">

        <div class="container">

            <div class="footer-grid">

                <!-- FOOTER 1 -->
                <div class="footer-section">

                    <h3>TechShop</h3>

                    <p class="text-muted">
                        Your one-stop shop for electronics.
                    </p>

                </div>

                <!-- FOOTER 2 -->
                <div class="footer-section">

                    <h3>Quick Links</h3>

                    <ul>

                        <li><a href="index.php">Home</a></li>

                        <li><a href="products.php">Products</a></li>

                        <li><a href="about.php">About</a></li>

                        <li><a href="contact.php">Contact</a></li>

                    </ul>

                </div>

                <!-- FOOTER 3 -->
                <div class="footer-section">

                    <h3>Contact</h3>

                    <ul>

                        <li>
                            <a href="mailto:info@techshop.com">
                                info@techshop.com
                            </a>
                        </li>

                        <li>
                            <a href="tel:+1234567890">
                                +1 234 567 890
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- COPYRIGHT -->
            <div class="footer-bottom">

                <p>
                    &copy; 2024 shoevinir.
                    All rights reserved.
                </p>

            </div>

        </div>

    </footer>

</body>
</html>