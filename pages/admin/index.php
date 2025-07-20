<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Required includes
require_once(__DIR__ . "/../../include/initialize.php");
require_once(__DIR__ . "/../../include/config.php");

// Session/Auth check
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] === "user") {
    header("location: ../../login/index.php");
    exit;
}

// Set default content
$view = $_GET['page'] ?? 'dashboard';
$content = 'dashboard.php';
$title = "Dashboard - " . SYSTEM_NAME;

// Page configuration map
$pages = [
    // [title, content file, active variable for sidebar]
    'dashboard'        => ['Dashboard', 'dashboard.php', 'dashboard'],
    'customers'        => ['Customers', 'customersTable.php', 'customers'],
    'deceased'         => ['Deceased', 'deceased.php', 'record'],
    'deceased_details' => ['Deceased Details', 'tabs/deceased_details.php', 'record'],
    'interment'        => ['Interment', 'intermentSetup.php', 'interment'],
    'shop'             => ['Shop', 'shop.php', 'shop'],
    'order'            => ['Order', 'order.php', 'order'],
    'receipt'          => ['Receipt', 'receipt.php', 'order'],
    'map'              => ['Map', 'map.php', 'map'],
    'activity'         => ['Activity', 'activity.php', 'activity'],
    'logout'           => ['Logout', 'logout.php', 'activity'],
    'profile'          => ['Profile', 'profile.php', 'profile'],

    // Function connector
    'add'              => ['New Record', 'function/add.php'],
    'merge'            => ['Merge Record', 'function/merge.php'],
    'payment'          => ['Check out', 'payment.php'],

    // Tabs
    'update_service'   => ['Update Service', 'tabs/update_service.php', 'shop'],
    'add_service'      => ['New Service', 'tabs/add_service.php', 'shop'],
    'add_employee'     => ['New Employee', 'tabs/add_employee.php', 'employee'],
    'insert'           => ['New Record', 'tabs/insert_record.php', 'record'],
    'merge_record'     => ['Merge Record', 'tabs/merge_record.php', 'record'],
    'new_record'       => ['New Record', 'tabs/new_record.php', 'record'],
    'add_order'        => ['New Order', 'tabs/add_order.php', 'order'],
    'select_order'     => ['Select Order', 'tabs/select_order.php', 'order'],
    'insert_plot'      => ['New plot', 'tabs/new_plot.php', 'map'],
    'add_img'          => ['Insert img', 'tabs/add_img.php', 'map'],
    'contact_person'   => ['Contact Person', 'tabs/contact_person.php', 'record'],
    'order_details'    => ['Order Details', 'tabs/order_details.php', 'order'],
    'edit_record'      => ['Update Record', 'tabs/edit_record.php', 'record'],
];

// Special case: admin-only page
if ($view === 'account') {
    if ($_SESSION["user_type"] === "admin") {
        $title = "Account - " . SYSTEM_NAME;
        $content = 'account.php';
        $account = 'active';
    } else {
        header("location: index.php?page=dashboard");
        exit;
    }
}

// Load matched page from list
elseif (isset($pages[$view])) {
    $page = $pages[$view];
    $title = $page[0] . " - " . SYSTEM_NAME;
    $content = $page[1];
    if (isset($page[2])) {
        ${$page[2]} = 'active';
    }
}

// Load template
require_once("template/admin_template.php");
