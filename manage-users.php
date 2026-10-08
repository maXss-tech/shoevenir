<?php
require_once('config.php');
require_once('auth.php');

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users - Admin</title>

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
    transition: 0.3s;
}

.admin-nav a:hover,
.admin-nav a.active {
    background: #2563eb;
    color: white;
}

/* MAIN CONTENT */
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

/* BADGES */
.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
}

.admin {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.user {
    background: rgba(59, 130, 246, 0.15);
    color: #2563eb;
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
        <a href="manage-orders.php">📦 Orders</a>
        <a href="manage-users.php" class="active">👥 Users</a>
        <a href="index.php">🏠 View Store</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

</div>

<!-- MAIN -->
<div class="admin-content">

    <h1 style="margin-bottom: 20px;">Manage Users</h1>

    <div class="table-container">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>
            </thead>

            <tbody>

            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5">No users found</td>
                </tr>
            <?php else: ?>

                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo sanitize($user['username']); ?></td>
                    <td><?php echo sanitize($user['email']); ?></td>

                    <td>
                        <span class="badge <?php echo $user['role']; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>

                    <td>
                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
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
