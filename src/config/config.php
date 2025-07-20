<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cemeterease-v2');

// URL Root
define('URLROOT', 'http://localhost/split');

// Site Name
define('SITENAME', 'CemeterEase System');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// PHPMailer for Email Configuration
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_USERNAME', 'cemeterease.memorial@gmail.com');
define('EMAIL_PASSWORD', 'ryhw bryw bmjm tscl');
define('EMAIL_PORT', 587);
define('EMAIL_FROM', 'cemeterease.memorial@gmail.com');

// HTTPSMS Configuration
define('HTTPSMS_API', 'uk_z17ZJyTtyfhTleWkpLBpJLj0Ui1UjI_6WnMk9k17eYnWsqRO3N7j80W-xTvTk2op');
define('HTTPSMS_FROM', '+639634636306');
define('HTTPSMS_API_URL', 'https://api.httpsms.com/v1/messages/send');



// // Load environment variables
// $env = parse_ini_file(dirname(dirname(__FILE__)) . '/.env');

// // Database configuration
// define('DB_HOST', $env['DB_HOST']);
// define('DB_USER', $env['DB_USER']);
// define('DB_PASS', $env['DB_PASS']);
// define('DB_NAME', $env['DB_NAME']);

// // URL Root
// define('URLROOT', $env['URLROOT']);

// // Site Name
// define('SITENAME', $env['SITENAME']);

// // App Root
// define('APPROOT', dirname(dirname(__FILE__)));

// // Email Configuration
// define('EMAIL_HOST', $env['EMAIL_HOST']);
// define('EMAIL_USERNAME', $env['EMAIL_USERNAME']);
// define('EMAIL_PASSWORD', $env['EMAIL_PASSWORD']);
// define('EMAIL_PORT', $env['EMAIL_PORT']);
// define('EMAIL_FROM', $env['EMAIL_FROM']);