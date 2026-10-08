<?php
/* =====================================================
   DELETE PRODUCT
   Removes product from database
   ===================================================== */

require_once '../config.php';
require_once '../auth.php';
require_once '../product-functions.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get product ID
$productId = intval($_GET['id'] ?? 0);

if ($productId) {
    deleteProduct($productId);
}

// Redirect back to dashboard
redirect('dashboard.php');
?>
