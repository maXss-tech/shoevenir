<?php
session_start();

// security check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<nav class="navbar">
    <div class="container">

        <div class="logo">Admin Panel</div>

        <ul class="nav-links">

    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="orders.php">Orders</a></li>
    <li><a href="../">Back to Shop</a></li>

    <li><a href="../logout.php">Logout</a></li>

</ul>

    </div>
</nav>