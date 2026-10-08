<?php
require_once('config.php');
require_once('auth.php');

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $status = sanitize($_POST['status']);

    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $orderId]);
}

// Get orders
global $pdo;
$orders = $pdo->query("
    SELECT o.*, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Orders - Admin</title>

<style>
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #f4f6fb;
}

/* SIDEBAR */
.admin-sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #111827, #0b1220);
    padding: 20px;
}

.admin-sidebar-logo {
    color: white;
    text-align: center;
    margin-bottom: 25px;
    font-size: 18px;
}

.admin-nav a {
    display: block;
    color: #cbd5e1;
    text-decoration: none;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 10px;
}

.admin-nav a:hover,
.admin-nav a.active {
    background: #2563eb;
    color: white;
}

/* MAIN */
.admin-content {
    margin-left: 260px;
    padding: 30px;
}

/* TABLE */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #2563eb;
    color: white;
    text-align: left;
    padding: 15px;
}

td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f3f4f6;
}

/* SELECT DROPDOWN */
select {
    padding: 6px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background: white;
}

/* BUTTON */
.btn {
    padding: 6px 10px;
    background: #2563eb;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    display: inline-block;
}

.btn:hover {
    background: #1d4ed8;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="admin-sidebar">

    <div class="admin-sidebar-logo">
        shoevinir
    </div>

    <div class="admin-nav">
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="add-product.php">➕ Add Product</a>
        <a href="manage-orders.php" class="active">📦 Orders</a>
        <a href="manage-users.php">👥 Users</a>
        <a href="index.php">🏠 View Store</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

</div>

<!-- MAIN CONTENT -->
<div class="admin-content">

    <h1 style="margin-bottom: 20px;">Manage Orders</h1>

    <div class="table-container">

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="8">No orders found</td>
                </tr>
            <?php else: ?>

                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo sanitize($order['username']); ?></td>
                    <td><?php echo sanitize($order['email']); ?></td>
                    <td><?php echo formatPrice($order['total_amount']); ?></td>
                    <td><?php echo sanitize($order['shipping_address']); ?></td>

                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Pending</option>
                                <option value="processing" <?= $order['status']=='processing'?'selected':'' ?>>Processing</option>
                                <option value="shipped" <?= $order['status']=='shipped'?'selected':'' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status']=='delivered'?'selected':'' ?>>Delivered</option>
                            </select>
                        </form>
                    </td>

                    <td>
                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                    </td>

                    <td>
                        <a class="btn" href="view-order.php?id=<?php echo $order['id']; ?>">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>
        </table>

    </div>

</div>

</body>
</html>