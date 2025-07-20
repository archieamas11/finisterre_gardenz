<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="<?php echo WEBROOT; ?>assets/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/compiled/css/app.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/L.Control.Layers.Tree.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/L.Control.Locate.min.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/qgis2web.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet-control-geocoder.Geocoder.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet-search.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/leaflet-tag-filter-button.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/easy-button.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>webmap/css/Leaflet.AnimatedSearchBox.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/mapOverview.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/leafletSearchBar.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/popup-modal.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/shop.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/userDashboardHomepage.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/toast.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/userBookings.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/general.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/mapFilter.style.css">
    <!-- Tables -->
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/compiled/css/table-datatable-jquery.css">
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>
</head>
<body>
    <div id="app">
        <style>
            .has-sub {
                position: relative;
                right: -600px;
                margin: 0;
                padding: 0;
            }
            
            /* Critical CSS for Map Display */
            #displayUserCemeteryMap {
                width: 100%;
                height: 100%;
                min-height: 600px;
                position: relative;
                z-index: 1;
            }
            
            .map-management-container {
                position: relative;
                height: calc(100vh - 200px);
                min-height: 600px;
                width: 100%;
            }

            .submenu {
                padding: 0;
                margin: 0;
            }

        </style>
        <div id="main" class="layout-horizontal">
            <header class="fixed-top">
                <!-- start of hortizontal navigation bar -->
                <nav class="main-navbar ">
                    <div class="container header-menu-container">
                        <div class="menu-container">
                            <ul>
                                <li class="menu-item <?php echo $home; ?>"><a class="menu-link" href="<?php echo WEBROOT; ?>pages/user/index.php?page=home"><span><i class="bi bi-grid-fill"></i> Home</span></a></li>
                                <li class="menu-item <?php echo $service; ?>"><a class="menu-link" href="<?php echo WEBROOT; ?>pages/user/index.php?page=service"><span><i class="bi bi-gear-fill"></i> Services</span></a></li>
                                <li class="menu-item <?php echo $orderStatus; ?>"><a class="menu-link" href="<?php echo WEBROOT; ?>pages/user/index.php?page=orderStatus"><span><i class="bi bi-hourglass-split"></i> Order Status</span></a></li>
                                <li class="menu-item <?php echo $map; ?>"><a class="menu-link" href="<?php echo WEBROOT; ?>pages/user/index.php?page=map"><span><i class="bi bi-map-fill"></i> Map</span></a></li>
                                <li class="menu-item has-sub">
                                    <a href="#" class="menu-link">
                                        <span><img src="<?php echo WEBROOT; ?>assets/compiled/jpg/1.jpg" alt="Avatar" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 5px;" /> <?php echo ucwords($_SESSION['name']); ?></span>
                                    </a>
                                    <div class="submenu">
                                        <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                                        <div class="submenu-group-wrapper">
                                        <ul class="submenu-group">
                                            <li class="submenu-item"><a href="<?php echo WEBROOT; ?>pages/user/index.php?page=profile">My Account</a></li>
                                            <li class="submenu-item"><a href="<?php echo WEBROOT; ?>pages/user/index.php?page=settings">Settings</a></li>
                                            <hr>
                                            <li class="submenu-item"><a href="<?php echo WEBROOT; ?>include/logout.php">Logout</a></li>
                                        </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </nav>
            </header>

            <div class="content-wrapper container" style="padding-top: 70px;">
                <div class="page-content" id="main-content">
                    <?php check_message();?>
                    <div id="sonner-toast-container" class="sonner-toast-container"></div>
                   <?php include($content);?>
                </div>
            </div>
        </div>
    </div>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/68249c3d8984c1190f3abff7/1ir7g4kiv';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

    <!-- For horizontal layout Navigation-->
    <script src="<?php echo WEBROOT; ?>assets/static/js/pages/horizontal-layout.js"></script>

    <!-- Development version lucide-->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>lucide.createIcons();</script>

    <script src="<?php echo WEBROOT; ?>assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/compiled/js/app.js"></script>

    <!-- user controller javascript -->
    <script>
        function loadContent(page) {
            fetch("../../assets/content/" + page + ".php?time=" + new Date().getTime())
            .then((res) => res.text())
            .then((html) => {
            document.getElementById("main-content").innerHTML = html;
            history.pushState({}, "", "?page=" + page);
            });
        }
    </script>


    <script src="<?php echo WEBROOT; ?>assets/extensions/jquery/jquery.min.js"></script>
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
    <script src="<?php echo WEBROOT; ?>webmap/js/easy-button.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-search.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/Leaflet.AnimatedSearchBox.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-tag-filter-button.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-hash.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/Autolinker.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/rbush.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/labelgun.min.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/labels.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/js/leaflet-control-geocoder.Geocoder.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/data/new_location_1.js"></script>
    <script src="<?php echo WEBROOT; ?>webmap/data/new_roads_2.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/toast.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/advancedSearch.js"></script>
    <script src="<?php echo WEBROOT; ?>assets/js/mapFilter.js"></script>


    <!-- Map Initialization Debug Script -->
    <script>
        // Debug script to check if map dependencies are loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🗺️ Map Debug Info:');
            console.log('- Leaflet loaded:', typeof L !== 'undefined');
            console.log('- Map container exists:', document.getElementById('displayUserCemeteryMap') !== null);
            console.log('- json_pointsfinall_3 loaded:', typeof json_pointsfinall_3 !== 'undefined');
            console.log('- json_new_location_1 loaded:', typeof json_new_location_1 !== 'undefined');
            console.log('- json_new_roads_2 loaded:', typeof json_new_roads_2 !== 'undefined');
            
            // Check if map container has proper dimensions
            const mapContainer = document.getElementById('displayUserCemeteryMap');
            if (mapContainer) {
                const rect = mapContainer.getBoundingClientRect();
                console.log('- Map container dimensions:', rect.width + 'x' + rect.height);
                if (rect.width === 0 || rect.height === 0) {
                    console.warn('⚠️ Map container has zero dimensions! This will prevent the map from displaying.');
                }
            }
        });
    </script>

    <!-- QGIS Map -->
    <?php include(__DIR__ . '/../content/user_cemetery_map.php'); ?>
</body>

</html>