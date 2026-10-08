<?php
/* =====================================================
   SHOPPING CART PAGE
   Displays items in cart and allows quantity updates
   ===================================================== */

require_once 'config.php';
require_once 'cart-functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = getCurrentUserId();
$cartItems = getCartItems($userId);
$cartTotal = getCartTotal($userId);
$cartCount = getCartCount($userId);

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $cartId => $quantity) {
        updateCartQuantity($cartId, (int)$quantity);
    }
    // Refresh data
    $cartItems = getCartItems($userId);
    $cartTotal = getCartTotal($userId);
    $cartCount = getCartCount($userId);
}

// Handle item removal
if (isset($_GET['remove'])) {
    removeFromCart((int)$_GET['remove']);
    redirect('cart.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart - TechShop</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">Tech<span>Shop</span></a>
            
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li class="dropdown">
                    <span class="dropdown-toggle"><?php echo sanitize($_SESSION['username']); ?></span>
                    <div class="dropdown-content">
                        <?php if (isAdmin()): ?>
                            <a href="admin/dashboard.php">Admin Panel</a>
                        <?php endif; ?>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
                <li>
                    <a href="cart.php" class="cart-icon">
                        🛒
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Cart Container -->
    <div class="container">
        <div class="cart-container">

            <!-- Cart Items -->
            <div class="cart-items">
                <h2 style="margin-bottom: 30px;">Shopping Cart</h2>

                <?php if (empty($cartItems)): ?>
                    <div class="product-card">
                        <div class="product-info text-center">
                            <p style="font-size: 3rem; margin-bottom: 20px;">🛒</p>
                            <h3>Your cart is empty</h3>
                            <p class="text-muted">Start shopping to add items to your cart.</p>
                            <a href="products.php" class="btn btn-primary" style="margin-top: 20px;">Browse Products</a>
                        </div>
                    </div>
                <?php else: ?>
                    <form method="POST" action="cart.php">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item">
                                <!-- Product Image -->
                                <?php if (!empty($item['image'])): ?>
                                    <img 
                                        src="<?php echo sanitize($item['image']); ?>" 
                                        alt="<?php echo sanitize($item['name']); ?>" 
                                        class="cart-item-image"
                                    >
                                <?php else: ?>
                                    <div class="cart-item-image no-image" 
                                         style="display:flex;align-items:center;justify-content:center;">
                                        📦
                                    </div>
                                <?php endif; ?>

                                <!-- Product Details -->
                                <div class="cart-item-details">
                                    <h3 class="cart-item-name"><?php echo sanitize($item['name']); ?></h3>
                                    <p class="cart-item-price"><?php echo formatPrice($item['price']); ?></p>

                                    <!-- Quantity Controls -->
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn quantity-minus">-</button>
                                        <span class="quantity-display"><?php echo $item['quantity']; ?></span>
                                        <input type="hidden" name="quantities[<?php echo $item['cart_id']; ?>]" 
                                               value="<?php echo $item['quantity']; ?>">
                                        <button type="button" class="quantity-btn quantity-plus">+</button>
                                    </div>
                                </div>

                                <!-- Item Total & Remove -->
                                <div style="text-align: right;">
                                    <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary-color);">
                                        <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                                    </p>
                                    <a href="cart.php?remove=<?php echo $item['cart_id']; ?>" 
                                       class="btn btn-danger" style="margin-top: 10px; padding: 8px 15px; font-size: 0.9rem;">
                                        Remove
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <button type="submit" name="update_cart" class="btn btn-secondary" style="margin-top: 20px;">
                            Update Cart
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 style="margin-bottom: 20px;">Order Summary</h3>

                <div style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="text-muted">Subtotal</span>
                        <span><?php echo formatPrice($cartTotal); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="text-muted">Shipping</span>
                        <span><?php echo $cartTotal >= 50 ? 'FREE' : '$5.99'; ?></span>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 1.3rem; font-weight: bold;">
                    <span>Total</span>
                    <span class="text-primary">
                        <?php 
                        $shipping = $cartTotal >= 50 ? 0 : 5.99;
                        echo formatPrice($cartTotal + $shipping); 
                        ?>
                    </span>
                </div>

                <?php if (!empty($cartItems)): ?>
                    <a href="checkout.php" class="btn btn-primary form-btn" style="margin-top: 20px;">
                        Proceed to Checkout
                    </a>
                <?php endif; ?>

                <a href="products.php" class="btn btn-secondary form-btn" style="margin-top: 10px; margin-left: 0;">
                    Continue Shopping
                </a>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer" style="margin-top: 50px;">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 TechShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Update hidden input when quantity changes
        document.querySelectorAll('.quantity-controls').forEach(function(control) {
            const display = control.querySelector('.quantity-display');
            const input = control.querySelector('input[type="hidden"]');
            const minusBtn = control.querySelector('.quantity-minus');
            const plusBtn = control.querySelector('.quantity-plus');
            
            minusBtn.addEventListener('click', function() {
                let val = parseInt(display.textContent);
                if (val > 1) {
                    val--;
                    display.textContent = val;
                    input.value = val;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let val = parseInt(display.textContent);
                val++;
                display.textContent = val;
                input.value = val;
            });
        });
    </script>
</body>
</html>