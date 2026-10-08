<?php
/* =====================================================
   PRODUCT FUNCTIONS
   Handles product operations: get, add, edit, delete
   ===================================================== */

require_once 'config.php';

/* =====================================================
   GET ALL PRODUCTS
   Retrieves all products from database
   ===================================================== */
function getAllProducts() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

/* =====================================================
   GET PRODUCT BY ID
   Retrieves a single product by its ID
   ===================================================== */
function getProductById($productId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    return $stmt->fetch();
}

/* =====================================================
   GET PRODUCTS BY CATEGORY
   Retrieves products filtered by category
   ===================================================== */
function getProductsByCategory($category) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY created_at DESC");
    $stmt->execute([$category]);
    return $stmt->fetchAll();
}

/* =====================================================
   GET ALL CATEGORIES
   Retrieves list of unique product categories
   ===================================================== */
function getAllCategories() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/* =====================================================
   ADD NEW PRODUCT (Admin)
   Creates a new product in database
   ===================================================== */
function addProduct($name, $description, $price, $category, $stock, $image = null) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, price, category, stock, image)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $description, $price, $category, $stock, $image]);
        
        return ['success' => true, 'message' => 'Product added successfully', 'id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to add product'];
    }
}

/* =====================================================
   UPDATE PRODUCT (Admin)
   Updates existing product information
   ===================================================== */
function updateProduct($id, $name, $description, $price, $category, $stock, $image = null) {
    global $pdo;
    
    try {
        if ($image) {
            // Update with new image
            $stmt = $pdo->prepare("
                UPDATE products
                SET name = ?, description = ?, price = ?, category = ?, stock = ?, image = ?
                WHERE id = ?
            ");
            $stmt->execute([$name, $description, $price, $category, $stock, $image, $id]);
        } else {
            // Update without changing image
            $stmt = $pdo->prepare("
                UPDATE products
                SET name = ?, description = ?, price = ?, category = ?, stock = ?
                WHERE id = ?
            ");
            $stmt->execute([$name, $description, $price, $category, $stock, $id]);
        }
        
        return ['success' => true, 'message' => 'Product updated successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update product'];
    }
}

/* =====================================================
   DELETE PRODUCT (Admin)
   Removes a product from database
   ===================================================== */
function deleteProduct($productId) {
    global $pdo;
    
    // Get product to find image file
    $product = getProductById($productId);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        
        // Delete image file if exists
        if ($product && $product['image']) {
            $imagePath = '../uploads/products/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        return ['success' => true, 'message' => 'Product deleted successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to delete product'];
    }
}

/* =====================================================
   UPLOAD PRODUCT IMAGE
   Handles image file upload
   ===================================================== */
function uploadProductImage($file) {
    // Allowed file types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    // Check file type
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Use JPG, PNG, GIF, or WebP'];
    }
    
    // Check file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File too large. Maximum 5MB'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_') . '.' . $extension;
    
    // Set upload directory
    $uploadDir = '../uploads/products/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file'];
}
?>
