<?php
/* =====================================================
   CHECKOUT PAGE
   Handles order placement
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

// Redirect if cart is empty
if (empty($cartItems)) {
    redirect('cart.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $zip = sanitize($_POST['zip'] ?? '');

    // Validate
    if (empty($address) || empty($city) || empty($zip)) {

        $error = 'Please fill in all shipping details';

    } else {

        // Shipping fee
        $shipping = $cartTotal >= 50 ? 0 : 5.99;

        // Final total
        $finalTotal = $cartTotal + $shipping;

        // Full address
        $shippingAddress = "$address, $city, $zip";

        try {

            global $pdo;

            // Create order
            $stmt = $pdo->prepare("
                INSERT INTO orders 
                (user_id, total, shipping_address)
                VALUES (?, ?, ?)
            ");

            $stmt->execute([
                $userId,
                $finalTotal,
                $shippingAddress
            ]);

            // Get order ID
            $orderId = $pdo->lastInsertId();

            // Add order items
            foreach ($cartItems as $item) {

                $stmt = $pdo->prepare("
                    INSERT INTO order_items 
                    (order_id, product_id, quantity, price) 
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            // Clear cart
            clearCart($userId);

            $success = "Order placed successfully! Your order number is #$orderId";

            // Empty cart display
            $cartItems = [];
            $cartTotal = 0;

       } catch (PDOException $e) {

    $error = $e->getMessage();
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - Shoevinir</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body class="bg-black text-white">

<!-- NAVBAR -->
<nav class="bg-[#0f172a] shadow-lg">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">

        <!-- LOGO -->
        <a href="index.php" class="text-3xl font-bold text-white">
            Shoe<span class="text-purple-500">vinir</span>
        </a>

        <!-- NAV LINKS -->
        <ul class="flex gap-6 text-lg">

            <li>
                <a href="index.php" class="hover:text-purple-400 transition">
                    Home
                </a>
            </li>

            <li>
                <a href="products.php" class="hover:text-purple-400 transition">
                    Products
                </a>
            </li>

            <li>
                <a href="cart.php" class="hover:text-purple-400 transition">
                    Cart
                </a>
            </li>

            <li>
                <a href="logout.php" class="hover:text-red-400 transition">
                    Logout
                </a>
            </li>

        </ul>
    </div>
</nav>

<!-- PAGE -->
<div class="container mx-auto px-6 py-10">

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- CHECKOUT FORM -->
        <div class="lg:col-span-2 bg-[#111827] rounded-3xl p-8 shadow-2xl">

            <h2 class="text-3xl font-bold mb-8">
                Checkout
            </h2>

            <!-- ERROR -->
            <?php if ($error): ?>

                <div class="bg-red-900 text-red-200 p-4 rounded-xl mb-6">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>

            <!-- SUCCESS -->
            <?php if ($success): ?>

                <div class="bg-green-900 text-green-200 p-4 rounded-xl mb-6">
                    <?php echo $success; ?>
                </div>

                <a href="index.php"
                   class="inline-block bg-purple-600 hover:bg-purple-700 transition px-6 py-3 rounded-xl font-bold">
                    Continue Shopping
                </a>

            <?php else: ?>

                <!-- FORM -->
                <form method="POST" action="checkout.php">

                    <h3 class="text-2xl font-semibold mb-6">
                        Shipping Address
                    </h3>

                    <!-- ADDRESS -->
                    <div class="mb-5">

                        <label class="block mb-2 font-semibold">
                            Street Address
                        </label>

                        <input type="text"
                               name="address"
                               required
                               placeholder="123 Main Street"
                               class="w-full p-4 rounded-xl bg-[#1f2937] border border-gray-700 focus:border-purple-500 outline-none">

                    </div>

                    <!-- CITY -->
                    <div class="mb-5">

                        <label class="block mb-2 font-semibold">
                            City
                        </label>

                        <input type="text"
                               name="city"
                               required
                               placeholder="New York"
                               class="w-full p-4 rounded-xl bg-[#1f2937] border border-gray-700 focus:border-purple-500 outline-none">

                    </div>

                    <!-- ZIP -->
                    <div class="mb-8">

                        <label class="block mb-2 font-semibold">
                            ZIP Code
                        </label>

                        <input type="text"
                               name="zip"
                               required
                               placeholder="10001"
                               class="w-full p-4 rounded-xl bg-[#1f2937] border border-gray-700 focus:border-purple-500 outline-none">

                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-700 transition py-4 rounded-xl text-lg font-bold">

                        Place Order

                    </button>

                </form>

            <?php endif; ?>

        </div>

        <!-- ORDER SUMMARY -->
        <?php if (!$success): ?>

        <div class="bg-[#111827] rounded-3xl p-8 shadow-2xl h-fit">

            <h3 class="text-2xl font-bold mb-6">
                Order Summary
            </h3>

            <!-- PRODUCTS -->
            <?php foreach ($cartItems as $item): ?>

                <div class="flex justify-between border-b border-gray-700 pb-4 mb-4">

                    <div>
                        <p class="text-gray-300">
                            <?php echo sanitize($item['name']); ?>
                        </p>

                        <p class="text-sm text-gray-500">
                            Quantity: <?php echo $item['quantity']; ?>
                        </p>
                    </div>

                    <div class="font-semibold">
                        <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                    </div>

                </div>

            <?php endforeach; ?>

            <!-- SUBTOTAL -->
            <div class="flex justify-between mb-4 text-gray-300">

                <span>Subtotal</span>

                <span>
                    <?php echo formatPrice($cartTotal); ?>
                </span>

            </div>

            <!-- SHIPPING -->
            <div class="flex justify-between mb-6 text-gray-300">

                <span>Shipping</span>

                <span>
                    <?php echo $cartTotal >= 50 ? 'FREE' : '$5.99'; ?>
                </span>

            </div>

            <!-- TOTAL -->
            <div class="flex justify-between text-2xl font-bold border-t border-gray-700 pt-6">

                <span>Total</span>

                <span class="text-purple-500">

                    <?php
                    $shipping = $cartTotal >= 50 ? 0 : 5.99;

                    echo formatPrice($cartTotal + $shipping);
                    ?>

                </span>

            </div>

        </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>