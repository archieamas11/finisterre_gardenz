<?php

// Simple dotenv loader function
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception('.env file not found at: ' . $path);
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments and empty lines
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue;
        }
        
        // Parse key=value pairs
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove quotes if present
        if (preg_match('/^(["\']).*\1$/', $value)) {
            $value = substr($value, 1, -1);
        }
        
        // Set environment variable if not already set
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Load environment variables
loadEnv(__DIR__ . '/../.env');

// Helper function to get environment variables with fallback
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

// Database Configuration
define('DB_SERVER', env('DB_SERVER', 'localhost'));
define('DB_USERNAME', env('DB_USERNAME', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', ''));
define('DB_NAME', env('DB_NAME', 'cemeterease'));

// System Name
define('SYSTEM_NAME', env('SYSTEM_NAME', 'CemeterEase'));

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

// PHPMailer Configuration
define('EMAIL_HOST', env('EMAIL_HOST', 'smtp.gmail.com'));
define('EMAIL_USERNAME', env('EMAIL_USERNAME'));
define('EMAIL_PASSWORD', env('EMAIL_PASSWORD'));
define('EMAIL_PORT', env('EMAIL_PORT', 587));
define('EMAIL_FROM', env('EMAIL_FROM'));

// Google API Configuration
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', WEBROOT . 'login/index.php');

// Cloudinary Configuration
define('CLOUDINARY_CLOUD_NAME', env('CLOUDINARY_CLOUD_NAME'));
define('CLOUDINARY_API_KEY', env('CLOUDINARY_API_KEY'));
define('CLOUDINARY_API_SECRET', env('CLOUDINARY_API_SECRET'));

// TextBee API Configuration
define('TEXTBEE_API_KEY', env('TEXTBEE_API_KEY'));
define('TEXTBEE_API_URL', env('TEXTBEE_API_URL'));
define('TEXTBEE_SENDER_ID', SYSTEM_NAME);

// Pusher Configuration
define('PUSHER_APP_ID', env('PUSHER_APP_ID'));
define('PUSHER_APP_KEY', env('PUSHER_APP_KEY'));
define('PUSHER_APP_SECRET', env('PUSHER_APP_SECRET'));
define('PUSHER_APP_CLUSTER', env('PUSHER_APP_CLUSTER', 'ap3'));
?>