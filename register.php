<?php
/* =====================================================
   REGISTRATION PAGE
   Handles new user registration
   ===================================================== */

require_once 'config.php';
require_once 'auth.php';

$error = '';
$success = '';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all fields';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        // Attempt registration
        $result = registerUser($username, $email, $password);
        if ($result['success']) {
            $success = 'Registration successful! You can now login.';
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Shoevinir</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<!-- NAVBAR -->
<nav class="navbar">
    <div class="container">
        <a href="index.php" class="logo">Shoe<span>vinir</span></a>
        <button class="mobile-menu-btn">☰</button>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li class="dropdown">
                <span class="dropdown-toggle">Account</span>
                <div class="dropdown-content">
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                </div>
            </li>
            <li>
                <a href="cart.php" class="cart-icon">🛒<span class="cart-badge">0</span></a>
            </li>
        </ul>
    </div>
</nav>

<!-- REGISTRATION FORM -->
<div class="form-container">
    <h2 class="form-title">Create Account</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required placeholder="Choose a username" minlength="3"
                value="<?php echo isset($_POST['username']) ? sanitize($_POST['username']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email"
                value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Create a password" minlength="6">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
        </div>

        <button type="submit" class="btn btn-primary form-btn">Register</button>
    </form>

    <p class="form-link">
        Already have an account? <a href="login.php">Login here</a>
    </p>
</div>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>