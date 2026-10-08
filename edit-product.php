<?php
/* =====================================================
   EDIT PRODUCT PAGE
   Admin can edit existing products
   ===================================================== */

require_once '../config.php';
require_once '../auth.php';
require_once '../product-functions.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get product ID from URL
$productId = intval($_GET['id'] ?? 0);

if (!$productId) {
    redirect('dashboard.php');
}

// Get product data
$product = getProductById($productId);

if (!$product) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = sanitize($_POST['category'] ?? '');
    $stock = intval($_POST['stock'] ?? 0);
    
    // Validate inputs
    if (empty($name) || empty($price)) {
        $error = 'Product name and price are required';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0';
    } else {
        // Handle image upload
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadProductImage($_FILES['image']);
            if ($uploadResult['success']) {
                $imageName = $uploadResult['filename'];
                // Delete old image
                if ($product['image']) {
                    $oldImage = '../uploads/products/' . $product['image'];
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }
            } else {
                $error = $uploadResult['message'];
            }
        }
        
        if (empty($error)) {
            // Update product
            $result = updateProduct($productId, $name, $description, $price, $category, $stock, $imageName);
            
            if ($result['success']) {
                $success = 'Product updated successfully!';
                // Refresh product data
                $product = getProductById($productId);
            } else {
                $error = $result['message'];
            }
        }
    }
}

$categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-logo">
                Tech<span style="color: #fff;">Shop</span> Admin
            </div>
            <ul class="admin-nav">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="add-product.php">➕ Add Product</a></li>
                <li><a href="manage-orders.php">📦 Orders</a></li>
                <li><a href="manage-users.php">👥 Users</a></li>
                <li><a href="../index.html">🏠 View Store</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            <h1 style="margin-bottom: 30px;">Edit Product</h1>
            
            <div class="form-container" style="margin: 0; max-width: 600px;">
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="edit-product.php?id=<?php echo $productId; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo sanitize($product['name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?php echo sanitize($product['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price ($) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0.01" required 
                               value="<?php echo $product['price']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="Electronics" <?php echo $product['category'] === 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                            <option value="Accessories" <?php echo $product['category'] === 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                            <option value="Gadgets" <?php echo $product['category'] === 'Gadgets' ? 'selected' : ''; ?>>Gadgets</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" min="0" 
                               value="<?php echo $product['stock']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <?php if ($product['image']): ?>
                            <img src="../uploads/products/<?php echo sanitize($product['image']); ?>" 
                                 style="max-width: 200px; display: block; margin-bottom: 10px; border-radius: 10px;">
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/*"
                               onchange="previewImage(this)">
                        <img class="image-preview" style="display: none; max-width: 200px; margin-top: 10px; border-radius: 10px;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary form-btn">Update Product</button>
                    <a href="dashboard.php" class="btn btn-secondary" style="margin-top: 10px;">Cancel</a>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
