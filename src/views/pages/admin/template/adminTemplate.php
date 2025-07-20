<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Page Meta Tags-->
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <title><?= $data['title'] ?? 'App' ?></title>
    <link rel="shortcut icon" href="<?php echo URLROOT; ?>/public/images/CemeterEase.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo URLROOT; ?>/public/images/CemeterEase.svg" type="image/png">
    <link rel="stylesheet" crossorigin href="<?php echo URLROOT; ?>/public/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="<?php echo URLROOT; ?>/public/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="<?php echo URLROOT; ?>/public/compiled/css/iconly.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" crossorigin href="<?php echo URLROOT; ?>/public/compiled/css/extra-component-sweetalert.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/leaflet.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/L.Control.Layers.Tree.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/L.Control.Locate.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/qgis2web.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/leaflet-control-geocoder.Geocoder.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/webmap/css/leaflet-search.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/popup-modal.css?=v11">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/legend.css?=v6">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/shop.css?=v2">

    <!-- Tables -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" crossorigin href="<?php echo URLROOT; ?>/public/compiled/css/table-datatable-jquery.css">

    <!-- Fix for custom scrollbar if JS is disabled-->
    <noscript>
        <style>
            /**
        * Reinstate scrolling for non-JS clients
        */
            .simplebar-content-wrapper {
                overflow: auto;
            }
        </style>
    </noscript>
</head>
<body>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                    <div class="logo-dashboard">
                            <a href="index.php"><img src="<?php echo URLROOT; ?>/public/images/CemeterEase.svg" alt="Logo" style="width: 150px; height: auto;"></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                role="img" class="iconify iconify--system-uicons" width="20" height="20"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                        opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <?php if (isset($data["role"]) && $data["role"] == "admin") { ?>
                            <!-- For Admin Dashboard sidebars -->
                            <li class="sidebar-item <?php echo $data['dashboard']; ?>"><a class="sidebar-link" href="?tab=dashboard"><i class="bi bi-grid-fill"></i><span>Deceased</span></a></li>
                            <li class="sidebar-item <?php echo $data['deceased']; ?>"><a class="sidebar-link" href="?tab=deceased"><i class="bi bi-person-fill-x"></i><span>Deceased</span></a></li>
                            <li class="sidebar-item"><a class="sidebar-link" href="?tab=logout"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a></li>
                        <?php } elseif (isset($data["role"]) && $_SESSION["role"] == "staff") { ?>
                            <!-- For Staff Dashboard sidebars -->
                            <li class="sidebar-item <?php echo $dashboard; ?>"><a class="sidebar-link" href="<?php echo URLROOT; ?>pages/admin/index.php?page=dashboard"><i class="bi bi-grid-fill"></i><span>Dashboard</span></a></li>
                            <li class="sidebar-item <?php echo $record; ?>"><a class="sidebar-link" href="<?php echo URLROOT; ?>pages/admin/index.php?page=deceased"><i class="bi bi-person-fill-x"></i><span>Deceased</span></a></li>
                            <li class="sidebar-item <?php echo $map; ?>"><a class="sidebar-link" href="<?php echo URLROOT; ?>pages/admin/index.php?page=map"><i class="bi bi-geo-alt-fill"></i><span>Map</span></a></li>
                            <li class="sidebar-item <?php echo $order; ?>"><a class="sidebar-link" href="<?php echo URLROOT; ?>pages/admin/index.php?page=order"><i class="bi bi-droplet-half"></i><span>Orders</span></a></li>
                            <li class="sidebar-item <?php echo $logout; ?>"><a class="sidebar-link" href="<?php echo URLROOT; ?>include/logout.php"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- dynamic main content -->
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <?php require_once $data['content']; printSession();?>
        </div>
    </div>
</body>

<script src="<?php echo URLROOT; ?>/public/static/js/initTheme.js"></script>

<script src="<?php echo URLROOT; ?>/public/static/js/pages/filepond.js"></script>
<script src="<?php echo URLROOT; ?>/public/static/js/components/dark.js"></script>
<script src="<?php echo URLROOT; ?>/public/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?php echo URLROOT; ?>/public/compiled/js/app.js"></script>
<script src="<?php echo URLROOT; ?>/public/extensions/jquery/jquery.min.js"></script>

<!-- Need: Apexcharts -->
<script src="<?php echo URLROOT; ?>/public/extensions/apexcharts/apexcharts.min.js"></script>
<script src="<?php echo URLROOT; ?>/public/static/js/pages/dashboard.js"></script>

    <!-- Tables -->
    <script src="<?php echo URLROOT; ?>/public/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/static/js/pages/datatables.js"></script>

    <!-- Alerts -->
    <script src="<?php echo URLROOT; ?>/public/extensions/sweetalert2/sweetalert2.min.js"></script>>
    <script src="<?php echo URLROOT; ?>/public/static/js/pages/sweetalert2.js"></script>>

    <!-- Filepond -->
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/filepond/filepond.js"></script>
    <script src="<?php echo URLROOT; ?>/public/extensions/toastify-js/src/toastify.js"></script>
    <script src="<?php echo URLROOT; ?>/public/static/js/pages/filepond.js"></script>

    <!-- QGIS -->
    <script src="<?php echo URLROOT; ?>/public/webmap/js/qgis2web_expressions.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/leaflet.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/L.Control.Layers.Tree.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/L.Control.Locate.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/leaflet.rotatedMarker.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/leaflet.pattern.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/leaflet-search.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/leaflet-hash.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/Autolinker.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/rbush.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/labelgun.min.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/labels.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/js/leaflet-control-geocoder.Geocoder.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/data/new_location_1.js"></script>
    <script src="<?php echo URLROOT; ?>/public/webmap/data/new_roads_2.js"></script>

</html>