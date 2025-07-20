<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <!-- System icon -->
    <link rel="shortcut icon" href="<?php echo WEBROOT; ?>assets/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" crossorigin href="<?php echo WEBROOT; ?>assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="<?php echo WEBROOT; ?>assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/L.Control.Locate.min.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/qgis2web.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet-control-geocoder.Geocoder.css">

    <!-- Leaflet Search -->
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet-search.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet-tag-filter-button.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/easy-button.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/Leaflet.AnimatedSearchBox.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/popup-modal.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/leafletSearchBar.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/shop.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/customerInformation.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/toast.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/mapOverview.css">

    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/adminDashboard.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/@fortawesome/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" crossorigin href="<?php echo WEBROOT; ?>assets/compiled/css/table-datatable-jquery.css">

    <!-- load pusher -->
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/pusherAPI.js"></script>

    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>
</head>
<body>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo-dashboard d-flex align-items-center justify-content-center w-100">
                            <a href="index.php"><img src="<?php echo WEBROOT; ?>assets/images/system-icon.svg" alt="Logo" style="width: 80px; height: auto;"></a>
                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu d-flex flex-column" style="height: 88%; overflow: hidden;">
                    <ul class="menu d-flex flex-column" style="height: 100%; overflow: hidden;">           
                        <!-- For Admin and Staff Dashboard Vertical sidebars -->
                        <li class="sidebar-item <?php echo $dashboard; ?>"><a class="sidebar-link" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=dashboard"><i class="bi bi-grid-fill"></i><span>Dashboard</span></a></li>
                        <li class="sidebar-item <?php echo $interment; ?>"><a class="sidebar-link" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=interment"><i class="bi bi-gear-fill"></i><span>Interment Setup</span></a></li>
                        <li class="sidebar-item <?php echo $map; ?>"><a class="sidebar-link" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=map"><i class="bi bi-geo-alt-fill"></i><span>Map</span></a></li>
                        <li class="sidebar-item <?php echo $shop; ?>"><a class="sidebar-link" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=shop"><i class="bi bi-palette2"></i></i><span>Services</span></a></li>
                        <li class="sidebar-item <?php echo $order; ?>"><a class="sidebar-link" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=order"><i class="bi bi-bag-heart-fill"></i><span>Orders</span></a></li>

                        <?php if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "admin") { ?>
                        <!-- For Admin Only Dashboard sidebars -->
                        <li class="sidebar-item <?php echo $account; ?>"><a class="sidebar-link" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=account"><i class="bi bi-person-circle"></i><span>Accounts</span></a></li>
                        <?php } ?>
                        <div class="mt-auto">
                            <div class="card shadow-sm rounded-20">
                                <div class="card-body p-2"> 
                                    <div class="dropdown">
                                        <a href="#" id="topbarUserDropdown" class="p-2 rounded-20 w-100 d-flex align-items-center justify-content-between dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md2">
                                                    <!-- This is temporary -->
                                                    <img src="<?php echo WEBROOT; ?>assets/compiled/jpg/1.jpg" alt="Avatar" /> 
                                                </div>
                                                <div class="text">
                                                    <?php 
                                                        $profile_result = mysqli_query($mysqli, "SELECT users.*, customers.* FROM tbl_users AS users 
                                                        JOIN tbl_customers AS customers ON users.user_id = customers.user_id WHERE customers.customer_id = '{$_SESSION['customer_id']}'");
                                                        $profile_data = mysqli_fetch_assoc($profile_result);
                                                    ?>
                                                    <h6 class="user-dropdown-name"><?php echo ucwords($profile_data['first_name'] . ' ' . $profile_data['last_name']); ?></h6>
                                                    <p class="user-dropdown-status text-sm text-muted"><?php echo ucwords($profile_data['user_type']); ?></p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- This is the dropdown menu for the user options -->
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-animated shadow rounded-20 mt-2 p-2" style="min-width: 150px; width: auto;" aria-labelledby="topbarUserDropdown">
                                            <li><a class="dropdown-item" href="<?php echo WEBROOT; ?>pages/admin/index.php?page=profile">My Account</a></li>
                                            <li><hr class="dropdown-divider"/></li>
                                            <li><a class="dropdown-item" href="<?php echo WEBROOT; ?>include/logout.php">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                
                    </ul>
                </div>
            </div>
        </div>
        <!-- main content -->
        <div id="main">
            <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
            <!-- <button id="showToastBtn">Show Test Notification</button> -->
            <div id="sonner-toast-container" class="sonner-toast-container"></div>
            
            <!-- This is function to display toast notifications saved in the session -->
            <?php check_message(); ?>

            <!-- This is where the dynamic content of the page will be displayed -->
            <?php include($content); ?>
        </div>
    </div>
    <script src="<?php echo WEBROOT; ?>assets/static/js/pages/filepond.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/compiled/js/app.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/jquery/jquery.min.js"></script>

    <!-- Development version lucide-->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>lucide.createIcons();</script>

    <!-- Tables -->
    <script src="<?php echo WEBROOT; ?>assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/static/js/pages/datatables.js"></script>

    <!-- Filepond -->
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/extensions/filepond/filepond.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/static/js/pages/filepond.js"></script>

    <!-- QGIS -->
    <script src="<?php echo WEBROOT; ?>webmap/js/qgis2web_expressions.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/L.Control.Locate.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet.rotatedMarker.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet.pattern.js"></script>

    <!-- Leaflet Tag Filter Button -->
    <script src="<?php echo WEBROOT; ?>webmap/js/easy-button.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-search.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/Leaflet.AnimatedSearchBox.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-tag-filter-button.js"></script>

    <!-- Leaflet Hash -->
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-hash.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/Autolinker.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/rbush.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/labelgun.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/labels.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-control-geocoder.Geocoder.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/data/new_location_1.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/data/new_roads_2.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/advancedSearch.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/toast.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/mapFilter.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/fileUpload.js"></script>

    <!-- Load QGIS Map -->
    <?php include(__DIR__ . '/../content/display_cemetery_map.php'); ?>
    <!-- Include Toast JavaScript -->
</body>
</html>