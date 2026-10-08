<?php
session_start();

ini_set('display_errors', 0); // 0 on live, 1 only when debugging
error_reporting(E_ALL);

// Works on both InfinityFree and Vercel
$host = getenv('DB_HOST') ?: "sql207.infinityfree.com";
$user = getenv('DB_USER') ?: "if0_42008008";
$pass = getenv('DB_PASS') ?: "PUT_REAL_PASSWORD_ONLY_ON_HOSTING";
$db   = getenv('DB_NAME') ?: "if0_42008008_shoevinir";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}
?>