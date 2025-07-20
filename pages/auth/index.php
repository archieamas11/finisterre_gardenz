<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Required includes
require_once __DIR__ . '/../../include/config.php';
require_once __DIR__ . '/../../include/database.php';
require_once __DIR__ . "/../../include/google_auth.php";
require_once __DIR__ . "/../../include/notificationsConfig.php";
require_once __DIR__ . '/../../controller/auth_controller.php';

// Initialize AuthController
$authController = new AuthController($mysqli);

// Set default content
$view = $_GET['page'] ?? 'login';
$auth_content = 'content/login.php';
$title = "Login - " . SYSTEM_NAME;

$auth_pages = [
    // [Title, Content File, active variable for sidebar]
    'login'           =>  ['Login', 'content/login.php'],
    'register'          =>  ['Sign Up', 'content/register.php'],
    'signup-board'      =>  ['Check Email', 'content/signup-board.php'],
    'confirmation'      =>  ['Confirmation', 'content/signup-confirmation.php'],
    'forgotPassword'      =>  ['Forgot Password', 'content/forgotPassword.php'],
    'resetPassword'      =>  ['Reset Password', 'content/resetPassword.php'],
];

if (isset($auth_pages[$view])) {
    $page = $auth_pages[$view];
    $title = $page[0] . " - " . SYSTEM_NAME;
    $auth_content = $page[1];
}

// Load template
require_once("template/auth_template.php");
