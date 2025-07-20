<?php
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Required includes
require_once("../../include/initialize.php");
require_once("../../include/config.php");

// Session/Auth check
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
    header("location: ../../login/index.php");
    exit;
}

// Default view and content
$view = $_GET['page'] ?? 'home';
$content = 'home.php';
$title = "Dashboard - " . SYSTEM_NAME;

// Page list
$pages = [
    'home'         => ['Dashboard', 'home.php', 'home'],
    'orderStatus'  => ['Orders', 'orderStatus.php', 'orderStatus'],
    'service'      => ['Services', 'service.php', 'service'],
    'request'      => ['Request Form', 'tabs/request_form.php', 'service'],
    'map'          => ['Cemetery Map', 'map.php', 'map'],
    'order_detail' => ['Order Details', 'tabs/order_detail.php', 'home'],
    'profile'      => ['Profile', 'content/user_profile.php', 'profile'],
    'book'         => ['Bookings', 'content/user_bookings.php', 'book'],

];

// Load page data if matched
if (isset($pages[$view])) {
    $page = $pages[$view];
    $title = $page[0] . " - " . SYSTEM_NAME;
    $content = $page[1];
    if (isset($page[2])) {
        ${$page[2]} = 'active';
    }
}

require_once("template/user_template.php");
