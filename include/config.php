<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cemeterease');

// System Name
define('SYSTEM_NAME', 'CemeterEase');

// Define APPROOT first as it can be used to construct WEBROOT
define('APPROOT', dirname(__DIR__));
// Dynamically define WEBROOT
// Determine protocol (http or https)
$protocol = 'http';
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) {
    $protocol = 'https';
} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
    // Check for reverse proxy header (common with ngrok, load balancers)
    $protocol = 'https';
} elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) === 'on') {
    // Specific to some load balancers like AWS ELB
    $protocol = 'https';
} elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
    // Fallback for some configurations where HTTPS is on but not setting the above
    $protocol = 'https';
}

// Get the host (e.g., localhost, yourdomain.com, or xxxx.ngrok.io when using ngrok)
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'; // Fallback to localhost if not set

// Determine the base path of the application relative to the document root
$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/') : '';
$appRootFileSystemPath = str_replace('\\', '/', APPROOT); // APPROOT is already defined above

$base_app_path = '';
// Calculate path relative to document root if possible
// Using stripos for case-insensitive comparison, important for Windows paths
if (!empty($documentRoot) && stripos($appRootFileSystemPath, $documentRoot) === 0) {
    $base_app_path = substr($appRootFileSystemPath, strlen($documentRoot));
} else {
    // Fallback: use the name of the application's root directory.
    // This is robust if the app isn't directly under DOCUMENT_ROOT in a way that's easily calculable,
    // or if DOCUMENT_ROOT is not what we expect (e.g. CLI execution).
    $base_app_path = '/' . basename($appRootFileSystemPath);
}

// Normalize the base_app_path: ensure it starts with a slash, ends with a slash, and handles root path correctly.
if (empty($base_app_path) || $base_app_path === '/' || $base_app_path === '//') {
    $base_app_path = '/'; // Application is at the web root
} else {
    $base_app_path = '/' . trim($base_app_path, '/') . '/'; // Application is in a subdirectory
}

define('WEBROOT', $protocol . '://' . $host . $base_app_path);

// $this_file = str_replace('\\', '/', __FILE__);
// $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// $web_root = str_replace(array($doc_root, "include/config.php"), '', $this_file);

// define('WEBROOT', $web_root);

// PHPMailer Configuration
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_USERNAME', 'cemeterease.memorial@gmail.com');
define('EMAIL_PASSWORD', 'ryhw bryw bmjm tscl');
define('EMAIL_PORT', 587);
define('EMAIL_FROM', 'cemeterease.memorial@gmail.com');

// Google API Configuration
define('GOOGLE_CLIENT_ID', '476653195318-u49fh3rogjraviievtraaa09t7sqv2v0.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', WEBROOT . 'login/index.php');

// Cloudinary Configuration
define('CLOUDINARY_CLOUD_NAME', 'djrkvgfvo');
define('CLOUDINARY_API_KEY', '695834455173873');
define('CLOUDINARY_API_SECRET', '98Des_R7jcctIfJy6yA81HPg3po');

// TextBee API Configuration
define('TEXTBEE_API_KEY', 'aef5a129-0587-45ef-8909-5946c1dea8b6');
define('TEXTBEE_API_URL', 'https://api.textbee.dev/api/v1/gateway/devices/6828be8fdb6bef3de8d2fd0b/send-sms');
define('TEXTBEE_SENDER_ID', SYSTEM_NAME);

// Pusher Configuration
define('PUSHER_APP_ID', '2001762');
define('PUSHER_APP_KEY', '2b048793924fa7a65cda');
define('PUSHER_APP_SECRET', 'bfc53ba1baef4bb5cef7');
define('PUSHER_APP_CLUSTER', 'ap3');
?>