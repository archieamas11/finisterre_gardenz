<?php
    $total_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(grave_id) as total_plots FROM grave_points"));
    $available_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'vacant'"));
    $reserved_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'reserved'"));
    $occupied_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'occupied1' OR status = 'occupied2'"));
    // Fetch all blocks dynamically
    $blocks_query = "SELECT DISTINCT block FROM grave_points ORDER BY block ASC";
    $blocks_result = mysqli_query($mysqli, $blocks_query);
    ?>
<style>
    .control-btn {
        display: block;
        width: 100%;
        margin-bottom: 5px;
        padding: 8px 12px;
        background: var(--primary-color);
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
        background: rgba(255, 255, 255, 0.95);
        position: absolute;
        top: 225px;
        right: 15px;
        width: 147px;
        max-height: 230px;
        z-index: 1000;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        font-size: 12px;
    }

    /* Loading state for apply button */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Enhanced filter button styling */
    .btn-filter-active {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        border-color: #28a745 !important;
        position: relative;
    }

    .btn-filter-active::before {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: #dc3545;
        border-radius: 50%;
        border: 2px solid white;
    }
</style>
<!-- Map Container -->
<section class="section">
    <div class="card">
        <div class="card-body p-0">
            <div class="map-management-container">
                <!-- Status Info -->
                <div class="status-info">
                    <div class="legend-card-header">
                        <h6 class="mb-0 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-info-circle me-2"></i>Status</span>
                            <button class="btn btn-sm btn-link" id="minimizeLegend"><i class="bi bi-dash-lg"></i></button>
                        </h6>
                    </div>
                    <div class="legend-card-body p-3" id="statusInfo">
                        <div><strong>Total Graves: </strong><span><?php echo $total_plots['total_plots']; ?></span></div>
                        <div><strong>Occupied: </strong><span><?php echo $occupied_plots['status']; ?></span></div>
                        <div><strong>Reserved: </strong><span><?php echo $reserved_plots['status']; ?></span></div>
                        <div><strong>Available: </strong><span><?php echo $available_plots['status']; ?></span></div>
                    </div>
                </div>

                <div class="legend" id="mapLegend">
                    <div class="legend-card">
                        <div class="legend-card-header">
                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-map me-2"></i>Legend</span>
                                <button class="btn btn-sm btn-link" id="minimizeLegend">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="legend-card-body p-3" id="legendContent">
                            <div class="legend-item mb-2">
                                <span class="legend-color available"></span>
                                <span class="legend-label">Available Plots</span>
                            </div>
                            <div class="legend-item mb-2">
                                <span class="legend-color occupied"></span>
                                <span class="legend-label">Occupied</span>
                            </div>
                            <div class="legend-item mb-2">
                                <span class="legend-color reserved"></span>
                                <span class="legend-label">Reserved</span>
                            </div>
                            <div class="legend-item mb-2">
                                <span class="legend-color road"></span>
                                <span class="legend-label">Road/Path</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color landmark"></span>
                                <span class="legend-label">Landmark</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Element -->
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
                </div>
                <!-- Display cemetery map with interactive features -->
                <div id="displayUserCemeteryMap"></div>
            </div>
        </div>
    </div>
</section>

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
            [10.251615149104896, 123.79578200208688]
        ]);
    }
}

function whereAmI() {
    if (typeof map !== 'undefined') {
        // Get user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const userLocation = L.latLng(lat, lng);
                
                // Center the map on user's location
                map.setView(userLocation, 18);
                
                // Add a marker for the user's location
                L.marker(userLocation).addTo(map)
                    .bindPopup('You are here!')
                    .openPopup();
            }, () => {
                alert('Unable to retrieve your location.');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
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

function showLegend() {
    // Toggle legend visibility
    const legend = document.querySelector('.legend');
    if (legend) {
        legend.style.display = legend.style.display === 'none' ? 'block' : 'none';
    }
}
</script>

<!-- Advanced Search Modal -->
<div class="modal fade" id="advancedSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-search me-2"></i>Advanced Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body h-100">
                <div class="alert alert-info mb-3" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Advanced Search:</strong> Please fill at least 3 fields for more accurate and specific
                    search results.
                </div>
                <div class="mb-3">
                    <label class="form-label">Deceased Name</label>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" placeholder="Enter full name or partial name"
                            aria-label="Username" aria-describedby="deceased-name" id="nameSearch">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Block</label>
                            <select class="form-select" id="graveBlockFilter">
                                <option hidden value="">Select Blocks</option>
                                <?php
                                if ($blocks_result && mysqli_num_rows($blocks_result) > 0) {
                                    while ($block = mysqli_fetch_assoc($blocks_result)) {
                                        echo '<option value="' . htmlspecialchars($block['block']) . '">Block ' . htmlspecialchars($block['block']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grave ID</label>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" placeholder="Enter grave ID" id="graveIdSearch">
                                <div class="form-control-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-select" id="genderFilter">
                        <option hidden value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Birth Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" placeholder="From year" id="birthYearFrom" min="1800"
                            max="2024">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" id="clearFilters">Clear</button>
                <button type="button" class="btn btn-primary" id="applySearch">Search</button>
            </div>
        </div>
    </div>
</div>

<!-- Map Filter Modal -->
<div class="modal fade" id="mapFilterModal" tabindex="-1" aria-labelledby="mapFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-funnel-fill me-2"></i>Cemetery Map Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Status Filter Section -->
                    <div class="col-md-6 mb-4">
                        <div class="filter-section">
                            <h6 class="filter-section-title">
                                <i class="bi bi-circle-fill me-2"></i>
                                Filter by Status
                            </h6>
                            <p class="text-muted small mb-3">Select grave statuses to display on the map</p>
                            <div class="status-filter-grid">
                                <div class="status-option" data-status="vacant">
                                    <div class="status-checkbox">
                                        <input type="checkbox" id="vacant" value="vacant" checked>
                                        <label for="vacant" class="status-label">
                                            <span class="status-color vacant-color"></span>
                                            <span class="status-text">
                                                <strong>Vacant</strong>
                                                <small
                                                    class="d-block text-muted"><?php echo $available_plots['status']; ?>
                                                    available</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="status-option" data-status="reserved">
                                    <div class="status-checkbox">
                                        <input type="checkbox" id="reserved" value="reserved" checked>
                                        <label for="reserved" class="status-label">
                                            <span class="status-color reserved-color"></span>
                                            <span class="status-text">
                                                <strong>Reserved</strong>
                                                <small
                                                    class="d-block text-muted"><?php echo $reserved_plots['status']; ?>
                                                    reserved</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="status-option" data-status="occupied1">
                                    <div class="status-checkbox">
                                        <input type="checkbox" id="occupied" value="occupied1" checked>
                                        <label for="occupied" class="status-label">
                                            <span class="status-color occupied-color"></span>
                                            <span class="status-text">
                                                <strong>Occupied</strong>
                                                <small
                                                    class="d-block text-muted"><?php echo $occupied_plots['status']; ?>
                                                    occupied</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Block Filter Section -->
                    <div class="col-md-6 mb-4">
                        <div class="filter-section">
                            <h6 class="filter-section-title">
                                <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                Filter by Block
                            </h6>
                            <p class="text-muted small mb-3">Select specific cemetery blocks to display</p>
                            <div class="block-filter-container">
                                <div class="block-select-all mb-2">
                                    <input type="checkbox" id="selectAllBlocks" checked>
                                    <label for="selectAllBlocks" class="form-label mb-0">
                                        <strong>All Blocks</strong>
                                    </label>
                                </div>
                                <div class="block-options-grid">
                                    <?php 
                                    // Reset the result pointer for blocks
                                    if ($blocks_result) {
                                        mysqli_data_seek($blocks_result, 0);
                                        while ($block = mysqli_fetch_assoc($blocks_result)): 
                                    ?>
                                    <div class="block-option">
                                        <input type="checkbox" id="block_<?php echo $block['block']; ?>"
                                            value="<?php echo $block['block']; ?>" class="block-checkbox" checked>
                                        <label for="block_<?php echo $block['block']; ?>" class="block-label">
                                            Block <?php echo $block['block']; ?>
                                        </label>
                                    </div>
                                    <?php 
                                        endwhile; 
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Filters Row -->
                <!-- <div class="row">
                    <div class="col-12">
                        <div class="filter-section">
                            <h6 class="filter-section-title">
                                <i class="bi bi-sliders me-2"></i>
                                Additional Filters
                            </h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="yearRange" class="form-label">Year Range</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" id="yearFrom" placeholder="From"
                                            min="1900" max="2024">
                                        <span class="input-group-text">to</span>
                                        <input type="number" class="form-control" id="yearTo" placeholder="To"
                                            min="1900" max="2024">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="privacyFilter" class="form-label">Privacy Settings</label>
                                    <select class="form-select form-select-sm" id="privacyFilter">
                                        <option value="all">All Records</option>
                                        <option value="public">Public Only</option>
                                        <option value="private">Private Only</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="searchRadius" class="form-label">Search Area</label>
                                    <select class="form-select form-select-sm" id="searchRadius">
                                        <option value="all">Entire Cemetery</option>
                                        <option value="section">Current Section</option>
                                        <option value="block">Current Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div class="filter-stats">
                        <small class="text-muted">
                            <span id="filterResultCount">Showing all <?php echo $total_plots['total_plots']; ?>
                                graves</span>
                        </small>
                    </div>
                    <div class="filter-actions">
                        <button type="button" class="btn btn-outline-secondary btn-sm me-2" id="resetFilters">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset All
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="applyFilters">
                            <i class="bi bi-check-lg me-1"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
/* Map Filter Modal Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #435ebe 0%, #5a67d8 100%);
}

.filter-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
}

.filter-section:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.filter-section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    font-size: 1rem;
}

.status-filter-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.status-option {
    background: white;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    overflow: hidden;
}

.status-option:hover {
    transform: scale(1.02);
}

.status-checkbox {
    position: relative;
}

.status-checkbox input[type="checkbox"] {
    display: none;
}

.status-label {
    display: flex;
    align-items: center;
    padding: 1rem;
    cursor: pointer;
    margin: 0;
    transition: all 0.3s ease;
}

.status-checkbox input[type="checkbox"]:checked+.status-label {
    background: rgba(0, 0, 0, 0.29);
    color: var(--primary-color);
    border: none;
}

.status-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-right: 0.75rem;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.vacant-color {
    background-color: #00FF00;
}

.reserved-color {
    background-color: #ffc107;
}

.occupied-color {
    background-color: #FF0000;
}

.status-text {
    flex: 1;
}

.status-text strong {
    font-size: 0.95rem;
}

.status-text small {
    font-size: 0.8rem;
    opacity: 0.8;
}

.block-filter-container {
    max-height: 200px;
    overflow-y: auto;
}

.block-select-all {
    background: white;
    padding: 0.75rem;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.block-select-all input[type="checkbox"] {
    margin-right: 0.5rem;
    transform: scale(1.1);
}

.block-options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.5rem;
    max-height: 120px;
    overflow-y: auto;
    padding: 0.5rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.block-option {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    background: #f8f9fa;
}

.block-option:hover {
    background: #e9ecef;
}

.block-checkbox {
    margin-right: 0.5rem;
    transform: scale(1.1);
}

.block-checkbox:checked+.block-label {
    font-weight: 600;
    color: #435ebe;
}

.block-label {
    font-size: 0.85rem;
    margin: 0;
    cursor: pointer;
}

.filter-stats {
    display: flex;
    align-items: center;
}

.filter-actions {
    display: flex;
    align-items: center;
}

/* Custom scrollbar for block filter */
.block-filter-container::-webkit-scrollbar,
.block-options-grid::-webkit-scrollbar {
    width: 6px;
}

.block-filter-container::-webkit-scrollbar-track,
.block-options-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.block-filter-container::-webkit-scrollbar-thumb,
.block-options-grid::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.block-filter-container::-webkit-scrollbar-thumb:hover,
.block-options-grid::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-lg {
        max-width: 95%;
    }

    .status-filter-grid {
        gap: 0.5rem;
    }

    .status-label {
        padding: 0.75rem;
    }

    .filter-section {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .block-options-grid {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 0.25rem;
    }
}

/* Animation for modal */
.modal.fade .modal-dialog {
    transform: translate(0, -50px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translate(0, 0);
}
</style>