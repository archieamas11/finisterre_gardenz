<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Cemetery Map Overview</h3>
                <p class="text-subtitle text-muted">Interactive map for locating plots.</p>
            </div>
        </div>
    </div>


    <!-- This is the html for the map page, which includes a navigation bar, a search container, and a legend for the map. -->
    <div id="map" class="map" style="width: 100%; height: calc(88vh - 200px); border-radius: 8px;">
        <nav class="nav-bar">
            <ul>
                <li id="search-container"></li>
            </ul>
        </nav>
        <div class="page-container">
            <div class="legend">
                <span class="legend-title">Legend</span>
                <div class="legend-item">
                    <span class="legend-color legend-vacant"></span>
                    <span class="legend-text">Available</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color legend-occupied"></span>
                    <span class="legend-text">Occupied</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color legend-road"></span>
                    <span class="legend-text">Road</span>
                </div>
            </div>
        </div>
    </div>
</div>