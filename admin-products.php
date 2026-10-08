<?php
// Force error visibility if the hosting container drops
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security restriction verification
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Isolated credentials matching config.php strings
$host = "sql207.infinityfree.com";
$user = "if0_42008008";
$pass = "studentdenz123";
$db = "if0_42008008_shoevinir";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";
$error = "";

/* DELETE PRODUCT HANDLER */
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Product deleted successfully!";
    } else {
        $error = "Failed to delete item from database.";
    }
}

/* UPDATE PRODUCT HANDLER */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $productId = intval($_POST['product_id']);
    $newPrice = floatval($_POST['price']);
    $newDesc  = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
    $newCat   = htmlspecialchars(trim($_POST['category']), ENT_QUOTES, 'UTF-8'); 

    // FALLBACK SAFE QUERY: Updates core columns to guarantee layout stability
    $stmt = mysqli_prepare($conn, "UPDATE products SET price = ?, description = ?, category = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "dssi", $newPrice, $newDesc, $newCat, $productId);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Product credentials updated successfully!";
    } else {
        $error = "Failed to update item values.";
    }
}

/* FETCH LIVE INVENTORY LISTING */
$products = [];
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Catalog - Admin</title>

<style>
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #f4f6fb;
}

/* SIDEBAR LAYOUT STYLES - Matched to your dashboard */
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

/* MAIN BODY FRAMEWORK */
.admin-content {
    margin-left: 260px;
    padding: 30px;
}

/* CONTAINER AND TABLES */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #2563eb;
    color: white;
    text-align: left;
    padding: 12px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

tr:hover {
    background: #f3f4f6;
}

/* MODULAR INLINE INPUT FRAMES */
.inline-input {
    width: 85px;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.inline-select {
    width: 110px;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: white;
}

.inline-textarea {
    width: 100%;
    min-width: 150px;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: inherit;
    resize: vertical;
}

/* INTERACTIVE BUTTONS */
.btn {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    display: inline-block;
    cursor: pointer;
    border: none;
}

.btn-update {
    background: #10b981;
    color: white;
}

.btn-update:hover {
    background: #059669;
}

.btn-delete {
    background: #ef4444;
    color: white;
    margin-left: 5px;
}

.btn-delete:hover {
    background: #dc2626;
}

/* NOTIFICATION MESSAGE BOXES */
.alert-error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}
</style>
</head>

<body>

<!-- SIDEBAR NAVIGATION MENU -->
<div class="admin-sidebar">
    <div class="admin-sidebar-logo">
        shoevinir
    </div>
    <div class="admin-nav">
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="add-product.php">➕ Add Product</a>
        <a href="admin-products.php" class="active">👟 Manage Inventory</a>
        <a href="manage-orders.php">📦 Orders</a>
        <a href="manage-users.php">👥 Users</a>
        <a href="index.php">🏠 View Store</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<!-- MAIN MANAGEMENT WORKING SCREEN -->
<div class="admin-content">
    <h1 style="margin-bottom: 20px;">Product Inventory Dashboard</h1>

    <?php if (!empty($message)): ?>
        <div class="alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Thumbnail</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th style="width: 110px;">Price ($)</th>
                    <th style="width: 160px; text-align: center;">Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                            Your store inventory database is currently empty.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://placeholder.com'; ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" 
                                     alt="Item Preview">
                            </td>

                            <td style="font-weight: 600; color: #1f2937;">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </td>

                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                
                                <td>
                                    <?php $currentCat = strtolower($product['category'] ?? ''); ?>
                                    <select name="category" class="inline-select" required>
                                        <option value="Electronics" <?php echo ($currentCat === 'electronics' || $currentCat === 'shoes') ? 'selected' : ''; ?>>shoes</option>
                                        <option value="Accessories" <?php echo ($currentCat === 'accessories' || $currentCat === 'hoodie') ? 'selected' : ''; ?>>hoodie</option>
                                        <option value="Gadgets" <?php echo ($currentCat === 'gadgets' || $currentCat === 'shirt') ? 'selected' : ''; ?>>shirt</option>
                                    </select>
                                </td>

                                <td>
                                    <textarea name="description" class="inline-textarea" rows="2"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                                </td>
                                <td>
                                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" class="inline-input" required>
                                </td>
                                
                                <td style="text-align: center; white-space: nowrap;">
                                    <button type="submit" name="update_product" class="btn btn-update">💾 Save</button>
                                    <a href="admin-products.php?delete_id=<?php echo $product['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you completely sure you want to permanently delete this item?');">🗑 Delete</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
