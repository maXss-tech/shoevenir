<?php

$error = "";
$success = "";

/* DATABASE CONNECTION */
$conn = mysqli_connect("localhost", "root", "", "shoevinir");

/* CHECK CONNECTION */
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* WHEN FORM IS SUBMITTED */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];

    $sql = "INSERT INTO products
    (name, description, price, category, stock, image)
    VALUES
    ('$name', '$description', '$price', '$category', '$stock', '$image')";

    if (mysqli_query($conn, $sql)) {
        $success = "Product added successfully!";
    } else {
        $error = "Error adding product.";
    }
}
?>


<body>

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
    font-size: 18px;
    margin-bottom: 30px;
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

/* FORM CARD */
.form-container {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    max-width: 600px;
}

/* INPUTS */
input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

/* BUTTON */
button {
    background: #2563eb;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #1d4ed8;
}

/* ALERTS */
.alert-error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
}
</style>

<div class="admin-sidebar">

    <div class="admin-sidebar-logo">
        shoevinir
    </div>

    <div class="admin-nav">
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="add-product.php" class="active">➕ Add Product</a>
        <a href="manage-orders.php">📦 Orders</a>
        <a href="manage-users.php">👥 Users</a>
        <a href="index.php">🏠 View Store</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

</div>

<div class="admin-content">

    <h1 style="margin-bottom: 20px;">Add New Product</h1>

    <div class="form-container">

        <?php
// safety check (prevents undefined variable warnings)
$error = $error ?? '';
$success = $success ?? '';
?>

<?php if (!empty($error)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
        <form method="POST" enctype="multipart/form-data">

            <label>Product Name *</label>
            <input type="text" name="name" required>

            <label>Description</label>
            <textarea name="description"></textarea>

            <label>Price *</label>
            <input type="number" name="price" step="0.01" required>

            <label>Category</label>
            <select name="category">
                <option value="">Select Category</option>
                <option value="Electronics">shoes</option>
                <option value="Accessories">Accessories</option>
                <option value="Gadgets">hoddie</option>
            </select>

            <label>Stock</label>
            <input type="number" name="stock" value="0">

            <label>Image URL</label>
    <input type="text" name="image" placeholder="https://example.com/image.jpg">

            <button type="submit">Add Product</button>

        </form>

    </div>

</div>

</body>