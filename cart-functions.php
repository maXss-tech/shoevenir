<?php
/* =====================================================
   SHOPPING CART FUNCTIONS
   Handles cart operations: add, remove, update, get
   ===================================================== */

require_once 'config.php';

// Handle AJAX requests


/* =====================================================
   ADD ITEM TO CART
   Adds a product to user's shopping cart
   ===================================================== */
function addToCart($productId, $quantity = 1) {
    global $pdo;
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        return ['success' => false, 'message' => 'Please login to add items to cart'];
    }
    
    $userId = getCurrentUserId();
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    $existingItem = $stmt->fetch();
    
    if ($existingItem) {
        // Update quantity if already in cart
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $productId, $quantity]);
    }
    
    // Get updated cart count
    $cartCount = getCartCount($userId);
    
    return ['success' => true, 'message' => 'Item added to cart', 'cartCount' => $cartCount];
}

/* =====================================================
   REMOVE ITEM FROM CART
   Removes a specific item from cart
   ===================================================== */
function removeFromCart($cartId) {
    global $pdo;
    
    if (!isLoggedIn()) {
        return ['success' => false, 'message' => 'Not logged in'];
    }
    
    $userId = getCurrentUserId();
    
    // Delete item (ensure it belongs to current user)
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartId, $userId]);
    
    return ['success' => true, 'message' => 'Item removed'];
}

/* =====================================================
   UPDATE CART QUANTITY
   Changes the quantity of an item in cart
   ===================================================== */
function updateCartQuantity($cartId, $quantity) {
    global $pdo;
    
    if (!isLoggedIn()) {
        return ['success' => false, 'message' => 'Not logged in'];
    }
    
    $userId = getCurrentUserId();
    
    if ($quantity < 1) {
        // Remove item if quantity is 0 or less
        return removeFromCart($cartId);
    }
    
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$quantity, $cartId, $userId]);
    
    return ['success' => true, 'message' => 'Quantity updated'];
}

/* =====================================================
   GET CART ITEMS
   Retrieves all items in user's cart with product details
   ===================================================== */
function getCartItems($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

/* =====================================================
   GET CART COUNT
   Returns total number of items in cart
   ===================================================== */
function getCartCount($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

/* =====================================================
   GET CART TOTAL
   Calculates total price of all cart items
   ===================================================== */
function getCartTotal($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT SUM(p.price * c.quantity) as total
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

/* =====================================================
   CLEAR CART
   Removes all items from user's cart (after checkout)
   ===================================================== */
function clearCart($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    return true;
}
?>
