<?php
// Fix for CSS/JS/images on Vercel
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/..' . $uri;

// If it's a CSS, JS, image file and exists, serve it directly
if ($file && file_exists($file) && is_file($file)) {
    if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2|woff)$/', $uri)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon'
        ];
        if (isset($mimes[$ext])) header("Content-Type: " . $mimes[$ext]);
        readfile($file);
        exit;
    }
}

// Otherwise load your shop
chdir(__DIR__ . '/..');
require __DIR__ . '/../index.php';
?>