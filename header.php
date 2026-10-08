<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ShoeVinir</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">
    <div class="container">

        <div class="logo">Shoe<span>Vinir</span></div>

        <ul class="nav-links">

            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>

            <!-- Admin access -->
            <li><a href="admin-login.php">Admin</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>

        </ul>

    </div>
</nav>