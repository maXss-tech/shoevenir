<?php
/* =====================================================
   ADMIN DASHBOARD
   ===================================================== */

session_start();
require_once('config.php');

// Protect admin page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit();
}

// Database statistics helper function for MySQLi
function getTableCount($connection, $tableName) {
    // Check if table exists to prevent crash loops
    $result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM `$tableName`");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    return 0; // Returns 0 if the table hasn't been created yet
}

// Fetch totals using active connection variable
$totalProducts = getTableCount($conn, 'products');
$totalUsers    = getTableCount($conn, 'users');
$totalOrders   = getTableCount($conn, 'orders');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            background:#f4f4f4;
        }

        /* Main Layout */
        .admin-container{
            display:flex;
            min-height:100vh;
        }

        /* Sidebar */
        .sidebar{
            width:250px;
            background:#111827;
            color:white;
            padding:20px;
        }

        .sidebar h2{
            margin-bottom:30px;
            text-align:center;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:12px;
            margin-bottom:10px;
            border-radius:8px;
            background:#1f2937;
        }

        .sidebar a:hover{
            background:#374151;
        }

        /* Main Content */
        .main-content{
            flex:1;
            padding:30px;
        }

        .main-content h1{
            margin-bottom:30px;
        }

        /* Cards */
        .card-container{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:20px;
            margin-bottom:40px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
            text-align:center;
        }

        .card h2{
            font-size:40px;
            color:#2563eb;
            margin-bottom:10px;
        }

        .card p{
            color:gray;
        }

        /* Table */
        table{
            width:100%;
            border-collapse:collapse;
            background:white;
            border-radius:10px;
            overflow:hidden;
        }

        table th,
        table td{
            padding:15px;
            border-bottom:1px solid #ddd;
            text-align:left;
        }

        table th{
            background:#2563eb;
            color:white;
        }

    </style>
</head>
<body>

<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">

        <h2>Admin Panel</h2>

        <a href="dashboard.php">📊 Dashboard</a>

        <a href="add-product.php">➕ Add Product</a>

        <a href="manage-orders.php">📦 Manage Orders</a>

        <a href="manage-users.php">👥 Manage Users</a>

        <a href="index.php">🏠 View Website</a>

        <a href="logout.php">🚪 Logout</a>

    </div>


    <!-- Main Content -->
    <div class="main-content">

        <h1>Welcome Admin</h1>


        <!-- Statistics Cards -->
        <div class="card-container">

            <div class="card">
                <h2><?php echo $totalProducts; ?></h2>
                <p>Total Products</p>
            </div>

            <div class="card">
                <h2><?php echo $totalUsers; ?></h2>
                <p>Total Users</p>
            </div>

            <div class="card">
                <h2><?php echo $totalOrders; ?></h2>
                <p>Total Orders</p>
            </div>

        </div>


        <!-- Sample Table -->
        <h2 style="margin-bottom:20px;">Quick Actions</h2>

        <table>

            <tr>
                <th>Feature</th>
                <th>Status</th>
            </tr>

            <tr>
                <td>Add Products</td>
                <td>Available</td>
            </tr>

            <tr>
                <td>Manage Orders</td>
                <td>Available</td>
            </tr>

            <tr>
                <td>Manage Users</td>
                <td>Available</td>
            </tr>

        </table>

    </div>

</div>

</body>
</html>
