<?php
/* =====================================================
   ADMIN DASHBOARD
   Main admin panel with overview
   ===================================================== */

require_once '../config.php';
require_once '../auth.php';
require_once '../product-functions.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
global $pdo;
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders")->fetchColumn();

// Get recent orders
$recentOrders = $pdo->query("
    SELECT o.*, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechShop</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- Admin Layout with Sidebar -->
    <div class="admin-layout">
        
        <!-- =====================================================
             ADMIN SIDEBAR
             Demonstrates CSS Layout (sidebar) and Overflow
             ===================================================== -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-logo">
                Tech<span style="color: #fff;">Shop</span> Admin
            </div>
            
            <ul class="admin-nav">
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="add-product.php">➕ Add Product</a></li>
                <li><a href="manage-orders.php">📦 Orders</a></li>
                <li><a href="manage-users.php">👥 Users</a></li>
                <li><a href="../index.html">🏠 View Store</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            <h1 style="margin-bottom: 30px;">Dashboard</h1>
            
            <!-- Stats Cards -->
            <div class="products-grid" style="margin-bottom: 40px;">
                <div class="product-card">
                    <div class="product-info text-center">
                        <div style="font-size: 2.5rem; color: var(--primary-color);"><?php echo $totalProducts; ?></div>
                        <p class="text-muted">Products</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-info text-center">
                        <div style="font-size: 2.5rem; color: var(--primary-color);"><?php echo $totalUsers; ?></div>
                        <p class="text-muted">Users</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-info text-center">
                        <div style="font-size: 2.5rem; color: var(--primary-color);"><?php echo $totalOrders; ?></div>
                        <p class="text-muted">Orders</p>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-info text-center">
                        <div style="font-size: 2.5rem; color: var(--success-color);"><?php echo formatPrice($totalRevenue); ?></div>
                        <p class="text-muted">Revenue</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders Table -->
            <h2 style="margin-bottom: 20px;">Recent Orders</h2>
            
            <!-- =====================================================
                 TABLE WITH CSS OVERFLOW
                 overflow-x: auto allows horizontal scrolling
                 ===================================================== -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOrders)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No orders yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo sanitize($order['username']); ?></td>
                                    <td><?php echo formatPrice($order['total_amount']); ?></td>
                                    <td>
                                        <span style="padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; background: rgba(108, 92, 231, 0.2); color: var(--primary-color);">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Quick Actions -->
            <h2 style="margin: 40px 0 20px;">Quick Actions</h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="add-product.php" class="btn btn-primary">Add New Product</a>
                <a href="manage-orders.php" class="btn btn-secondary">View All Orders</a>
                <a href="manage-users.php" class="btn btn-secondary">Manage Users</a>
            </div>
        </main>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
