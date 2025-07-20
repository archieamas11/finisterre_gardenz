<style>
/* Map Styles */
#displayMap {
    height: 100%;
    min-height: 800px;
    border-radius: 8px;
    overflow: hidden;
}

</style>

<?php
require_once __DIR__ . '/../cemetery_map.php';

renderCemeteryMapOverview($mysqli);