<?php 
include_once '../include/config.php';
include_once '../include/database.php'; 
    $total_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(grave_id) as total_plots FROM grave_points"));
    $available_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'vacant'"));
    $reserved_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'reserved'"));
    $occupied_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'occupied1' OR status = 'occupied2'"));

    // Fetch all blocks dynamically
    $blocks_query = "SELECT DISTINCT block FROM grave_points ORDER BY block ASC";
    $blocks_result = mysqli_query($mysqli, $blocks_query);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Custom CSS -->
    <link rel="stylesheet" crossorigin href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="css/map-filter.css">
    <link rel="stylesheet" href="css/popup-modal.css">
    <link rel="stylesheet" href="css/map-overview.css">
    <link rel="stylesheet" href="css/leaflet-searchbar.css">
    <link rel="stylesheet" href="css/floating-navbar.css">
    <link rel="stylesheet" href="css/Leaflet.AnimatedSearchBox.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="css/leaflet.css">
    <link rel="stylesheet" href="css/L.Control.Layers.Tree.css">
    <link rel="stylesheet" href="css/L.Control.Locate.min.css">
    <link rel="stylesheet" href="css/qgis2web.css">
    <link rel="stylesheet" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/leaflet.photon.css">
    <link rel="stylesheet" href="css/leaflet-measure.css">
    <title>Finisterre - Map</title>

    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://kit.fontawesome.com/665d03e83d.js" crossorigin="anonymous"></script>
    <style>
    #map {
        width: 100%;
        height: 100vh;
    }
    </style>
</head>

<body>
    <main>
        <!-- Display finisterre map here -->
        <div id="map">
            <div class="searchbar-container d-flex gap-3 justify-content-center align-items-center">
                <div class="searchbar" id="search-container"></div>
                <div class="button-wrapper">
                    <a href="#" class="advanced-search-icon" data-bs-toggle="modal"
                        data-bs-target="#advancedSearchModal" title="Advanced Search" aria-label="Advanced Search"
                        data-bs-placement="bottom">
                        <i class="bi bi-sliders"></i>
                    </a>
                </div>
                <div class="button-wrapper">
                    <button class="advanced-search-icon" onclick="filterMap()" data-bs-toggle="modal"
                        data-bs-target="#mapFilterModal">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
                <div class="button-wrapper">
                    <button class="advanced-search-icon" onclick="toggleFullscreen()">
                        <i class="bi bi-fullscreen"></i>
                    </button>
                </div>
                <div class="button-wrapper">
                    <button class="advanced-search-icon" onclick="centerMap()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                <div class="button-wrapper">
                    <button class="advanced-search-icon" onclick="whereAmI()">
                        <i class="bi bi-person-arms-up"></i>
                    </button>
                </div>
                <div class="button-wrapper">
                    <a href="../index.html" class="advanced-search-icon">
                        <i class="bi bi-house-door"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
    <script src="js/qgis2web_expressions.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/L.Control.Layers.Tree.min.js"></script>
    <script src="js/L.Control.Locate.min.js"></script>
    <script src="js/leaflet-svg-shape-markers.min.js"></script>
    <script src="js/leaflet.rotatedMarker.js"></script>
    <script src="js/leaflet.pattern.js"></script>
    <script src="js/leaflet-hash.js"></script>
    <script src="js/Autolinker.min.js"></script>
    <script src="js/rbush.min.js"></script>
    <script src="js/labelgun.min.js"></script>
    <script src="js/labels.js"></script>
    <script src="js/leaflet.photon.js"></script>
    <script src="js/leaflet-measure.js"></script>
    <script src="data/Chapel_1.js"></script>
    <script src="data/parking_2.js"></script>
    <script src="data/Clusters_3.js"></script>
    <script src="data/category_5.js"></script>
    <script src="data/entrance_6.js"></script>
    <script src="data/exit_7.js"></script>
    <script src="data/path_1.js"></script>
    <script src="data/boundary_0.js"></script>
    <!-- <script src="js/toggle-legend.js"></script>
    <script src="js/advanced-search.js"></script> -->
    <script src="js/map-filter.js"></script>
    <script src="js/Leaflet.AnimatedSearchBox.js"></script>
    <script>
    lucide.createIcons();
    </script>

    <?php include __DIR__ . '/finisterre_map.php'; ?>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>