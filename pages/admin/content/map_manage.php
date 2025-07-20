<?php
// Include necessary configuration and database connection
require_once __DIR__ . '/../../../include/config.php';
require_once __DIR__ . '/../../../include/database.php';
require_once __DIR__ . '/../../../include/auth_session.php';
?>

    <!-- Additional management styles -->
    <style>
        .map-management-header {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .map-management-container {
            position: relative;
            height: calc(100vh - 200px);
            min-height: 600px;
        }
        
        #map {
            height: 100%;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .management-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: rgba(255,255,255,0.95);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .control-btn {
            display: block;
            width: 100%;
            margin-bottom: 5px;
            padding: 8px 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .control-btn:hover {
            background: #0056b3;
        }
        
        .status-info {
            background: rgba(255,255,255,0.95);
            position: absolute;
            bottom: 10px;
            left: 10px;
            z-index: 1000;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div id="app"> 
        <div id="main">

            
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Map Management</h3>
                            <p class="text-subtitle text-muted">Manage cemetery grave locations and information</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Map Management</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
                <!-- Map Management Header -->
                <div class="map-management-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-2">Cemetery Interactive Map</h5>
                            <p class="mb-0 text-muted">View and manage grave locations, deceased information, and cemetery layout</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary btn-sm" onclick="refreshMap()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh Map
                            </button>
                            <button class="btn btn-info btn-sm" onclick="centerMap()">
                                <i class="bi bi-geo-alt"></i> Center View
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Map Container -->
                <section class="section">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="map-management-container">
                                <!-- Management Controls -->
                                <div class="management-controls">
                                    <button class="control-btn" onclick="toggleFullscreen()">
                                        <i class="bi bi-fullscreen"></i> Fullscreen
                                    </button>
                                    <button class="control-btn" onclick="exportMap()">
                                        <i class="bi bi-download"></i> Export
                                    </button>
                                    <button class="control-btn" onclick="showLegend()">
                                        <i class="bi bi-list"></i> Legend
                                    </button>
                                </div>
                                
                                <!-- Status Info -->
                                <div class="status-info">
                                    <div><strong>Total Graves:</strong> <span id="totalGraves">Loading...</span></div>
                                    <div><strong>Occupied:</strong> <span id="occupiedGraves">Loading...</span></div>
                                    <div><strong>Available:</strong> <span id="availableGraves">Loading...</span></div>
                                </div>
                                
                                <!-- Map Element -->
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <!-- QGIS Map Implementation -->
    <?php include(__DIR__ . '/../../../assets/content/QGIS-Map.php'); ?>
    
    <!-- Management Functions -->
    <script>
        // Management specific functions
        function refreshMap() {
            if (typeof map !== 'undefined') {
                map.invalidateSize();
                location.reload();
            }
        }
        
        function centerMap() {
            if (typeof map !== 'undefined') {
                map.fitBounds([
                    [10.251298497028595, 123.79519034872163],
                    [10.251931801181197, 123.79637365545214]
                ]);
            }
        }
        
        function toggleFullscreen() {
            const mapContainer = document.querySelector('.map-management-container');
            if (!document.fullscreenElement) {
                mapContainer.requestFullscreen().then(() => {
                    setTimeout(() => {
                        if (typeof map !== 'undefined') {
                            map.invalidateSize();
                        }
                    }, 100);
                });
            } else {
                document.exitFullscreen().then(() => {
                    setTimeout(() => {
                        if (typeof map !== 'undefined') {
                            map.invalidateSize();
                        }
                    }, 100);
                });
            }
        }
        
        function exportMap() {
            // Implement map export functionality
            alert('Map export functionality would be implemented here');
        }
        
        function showLegend() {
            // Toggle legend visibility
            const legend = document.querySelector('.legend');
            if (legend) {
                legend.style.display = legend.style.display === 'none' ? 'block' : 'none';
            }
        }
        
        // Update statistics after map loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(updateMapStatistics, 2000);
        });
        
        function updateMapStatistics() {
            if (typeof json_pointsfinall_3 !== 'undefined' && json_pointsfinall_3.features) {
                const features = json_pointsfinall_3.features;
                const totalGraves = features.length;
                const occupiedGraves = features.filter(f => f.properties.Status === 'Occupied').length;
                const availableGraves = features.filter(f => f.properties.Status === 'Available').length;
                
                document.getElementById('totalGraves').textContent = totalGraves;
                document.getElementById('occupiedGraves').textContent = occupiedGraves;
                document.getElementById('availableGraves').textContent = availableGraves;
            }
        }
        
        // Ensure map resizes properly on window resize
        window.addEventListener('resize', function() {
            if (typeof map !== 'undefined') {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            }
        });
    </script>
