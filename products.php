<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'cart-functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}


/*
|--------------------------------------------------------------------------
| PRODUCTS ARRAY
|--------------------------------------------------------------------------
*/

$conn = mysqli_connect("localhost", "root", "", "shoevinir");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT * FROM products");

$products = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
/*
|--------------------------------------------------------------------------
| ADD TO CART
|--------------------------------------------------------------------------
*/



if (isset($_POST['add_to_cart'])) {

    $productId = (int) $_POST['product_id'];

    addToCart($productId, 1);

    redirect('cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - TechShop</title>

    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="container">

            <a href="index.php" class="logo">
                Tech<span>Shop</span>
            </a>

            <ul class="nav-links">

                <li>
                    <a href="index.php">Home</a>
                </li>

                <li>
                    <a href="products.php">Products</a>
                </li>

                <li>
                    <a href="about.php">About</a>
                </li>

                <li>
                    <a href="contact.php">Contact</a>
                </li>

                <!-- ACCOUNT -->
                <li class="dropdown">

    <span class="dropdown-toggle">
        Account
    </span>

    <div class="dropdown-content">

        <?php if (isLoggedIn()): ?>

            <a href="logout.php">
                Logout
            </a>

        <?php else: ?>

            <a href="login.php">
                Login
            </a>

            <a href="register.php">
                Register
            </a>

        <?php endif; ?>

    </div>

</li>

                <!-- CART -->
                <li>

                    <a href="cart.php" class="cart-icon">

                        🛒

                        <span class="cart-badge">

    <?php echo getCartCount(getCurrentUserId()); ?>

</span>
                    </a>

                </li>

            </ul>

        </div>

    </nav>

    <!-- HEADER -->
    <header class="page-header">

        <h1>Our Products</h1>

        <p>
            Explore our collection of premium shoes
        </p>

    </header>

    <!-- PRODUCTS -->
    <section class="section">

        <div class="container">

            <div class="products-grid">

                <?php foreach ($products as $product): ?>

                    <div class="product-card">

                        <!-- IMAGE -->
                        <img
                            src="<?php echo $product['image']; ?>"
                            class="rounded-2xl h-64 w-full object-cover"
                        >

                        <!-- INFO -->
                        <div class="product-info">

                            <span class="product-category">
                                Shoes
                            </span>

                            <h3 class="product-name">
                                <?php echo $product['name']; ?>
                            </h3>

                            <p class="product-price">
                                <?php echo $product['price']; ?>
                            </p>

                            <!-- ADD TO CART -->
                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php echo $product['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    name="add_to_cart"
                                    class="btn btn-primary"
                                >
                                    Add to Cart
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer class="footer">

        <div class="container">

            <p>
                &copy; 2024 shoevinir.
                All rights reserved.
            </p>

        </div>

    </footer>

</body>

</html>