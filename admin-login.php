<?php
session_start();
require_once('config.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {

        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid admin login!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Shoevinir</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-black text-white min-h-screen flex items-center justify-center">

    <!-- Background Glow -->
    <div class="absolute w-96 h-96 bg-purple-600 rounded-full blur-3xl opacity-20 top-10 left-10"></div>
    <div class="absolute w-96 h-96 bg-pink-500 rounded-full blur-3xl opacity-20 bottom-10 right-10"></div>

    <!-- Login Card -->
    <div class="relative bg-gray-900/90 backdrop-blur-lg border border-gray-800 rounded-3xl p-10 w-full max-w-md shadow-2xl">

        <!-- Logo -->
        <h1 class="text-4xl font-bold text-center mb-2">
            <span class="text-purple-500">Shoe</span>vinir
        </h1>

        <p class="text-gray-400 text-center mb-8">
            Admin Control Panel
        </p>

        <!-- Error Message -->
        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded-xl mb-5 text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="space-y-5">

            <div>
                <label class="block mb-2 text-gray-300">
                    Admin Email
                </label>

                <input 
                    type="email" 
                    name="email" 
                    placeholder="Enter admin email"
                    required
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500"
                >
            </div>

            <div>
                <label class="block mb-2 text-gray-300">
                    Password
                </label>

                <input 
                    type="password" 
                    name="password" 
                    placeholder="Enter password"
                    required
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500"
                >
            </div>

            <!-- Button -->
            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 hover:scale-105 transition duration-300 py-3 rounded-xl font-semibold text-lg"
            >
                Login as Admin
            </button>

        </form>

        <!-- Back -->
        <div class="text-center mt-6">
            <a href="index.php" class="text-gray-400 hover:text-purple-400 transition">
                ← Back to Website
            </a>
        </div>

    </div>

</body>
</html>