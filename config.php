<?php

// Create a safe, private session path inside your htdocs folder
$sessionPath = __DIR__ . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0700, true);
}
session_save_path($sessionPath);

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "sql207.infinityfree.com";
$user = "if0_42008008";
$pass = "make your pass"; // Ensure this matches your hosting account password!
$db = "if0_42008008_shoevinir";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Get user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Format price
function formatPrice($price) {
    return '$' . number_format($price, 2);
}
// studentdenz123
?>