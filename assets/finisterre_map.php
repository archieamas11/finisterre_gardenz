<?php include __DIR__ . '/finisterre_data.php'; ?>

<!-- Leaflet Routing Machine for navigation -->
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<!-- Leaflet Locate Control for GPS tracking -->
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />

<script>
var highlightLayer;

function highlightFeature(e) {
    highlightLayer = e.target;
    highlightLayer.openPopup();
}
var map = L.map('map', {
    zoomControl: false,
    maxZoom: 28,
    minZoom: 2
}).fitBounds([
    [10.247883800064669, 123.79691285546676],
    [10.249302749341647, 123.7988598710129]
]);
var hash = new L.Hash(map);
map.attributionControl.setPrefix(
    '<a href="https://github.com/tomchadwin/qgis2web" target="_blank">qgis2web</a> &middot; <a href="https://leafletjs.com" title="A JS library for interactive maps">Leaflet</a> &middot; <a href="https://qgis.org">QGIS</a>'
);
var autolinker = new Autolinker({
    truncate: {
        length: 30,
        location: 'smart'
    }
});
// remove popup's row if "visible-with-data"
function removeEmptyRowsFromPopupContent(content, feature) {
    var tempDiv = document.createElement('div');
    tempDiv.innerHTML = content;
    var rows = tempDiv.querySelectorAll('tr');
    for (var i = 0; i < rows.length; i++) {
        var td = rows[i].querySelector('td.visible-with-data');
        var key = td ? td.id : '';
        if (td && td.classList.contains('visible-with-data') && feature.properties[key] == null) {
            rows[i].parentNode.removeChild(rows[i]);
        }
    }
    return tempDiv.innerHTML;
}
// add class to format popup if it contains media
function addClassToPopupIfMedia(content, popup) {
    var tempDiv = document.createElement('div');
    tempDiv.innerHTML = content;
    if (tempDiv.querySelector('td img')) {
        popup._contentNode.classList.add('media');
        // Delay to force the redraw
        setTimeout(function() {
            popup.update();
        }, 10);
    } else {
        popup._contentNode.classList.remove('media');
    }
}

// Measure Control
var measureControl = new L.Control.Measure({
    position: 'topright',
    primaryLengthUnit: 'meters',
    secondaryLengthUnit: 'kilometers',
    primaryAreaUnit: 'sqmeters',
    secondaryAreaUnit: 'hectares'
});
measureControl.addTo(map);
document.getElementsByClassName('leaflet-control-measure-toggle')[0].innerHTML = '';
document.getElementsByClassName('leaflet-control-measure-toggle')[0].className += ' fas fa-ruler';

// Navigation Controls - GPS tracking and routing functionality
var locateControl = L.control.locate({
    position: 'topleft',
    icon: 'fas fa-location-arrow',
    iconLoading: 'fas fa-spinner fa-spin',
    strings: {
        title: "Show my location",
        popup: "You are here! (within {distance} {unit})",
        outsideMapBoundsMsg: "You seem to be located outside the boundaries of the cemetery"
    },
    locateOptions: {
        maxZoom: 20,
        watch: true,
        enableHighAccuracy: true,
        maximumAge: 10000,
        timeout: 10000
    }
}).addTo(map);

// Global variables for navigation
var userLocationMarker = null;
var currentRoute = null;
var isNavigating = false;
var destinationCoords = null;

// Cemetery-aware routing variables
var cemeteryBounds = null;
var cemeteryPaths = [];
var pathGraph = new Map(); // For A* pathfinding
var isCustomRoutingActive = false;

/**
 * Initialize cemetery boundaries and path network for custom routing
 */
function initializeCemeteryRouting() {
    // Define cemetery boundaries from your coordinate data
    cemeteryBounds = L.latLngBounds([
        [10.247883800064669, 123.79691285546676], // Southwest corner
        [10.249302749341647, 123.7988598710129]   // Northeast corner
    ]);
    
    // Process path data from path_1.js to create routing graph
    if (typeof json_path_1 !== 'undefined') {
        processCemeteryPaths();
        buildPathGraph();
        console.log('Cemetery routing initialized with', cemeteryPaths.length, 'path segments');
    }
}

/**
 * Process cemetery path data into usable format for routing
 */
function processCemeteryPaths() {
    cemeteryPaths = [];
    
    json_path_1.features.forEach((feature, index) => {
        if (feature.geometry.type === 'MultiLineString') {
            feature.geometry.coordinates.forEach((lineString, lineIndex) => {
                const pathSegment = {
                    id: `path_${index}_${lineIndex}`,
                    coordinates: lineString,
                    nodes: []
                };
                
                // Convert coordinates to nodes with calculated distances
                for (let i = 0; i < lineString.length; i++) {
                    const coord = lineString[i];
                    const node = {
                        id: `${pathSegment.id}_node_${i}`,
                        lat: coord[1],
                        lng: coord[0],
                        pathId: pathSegment.id,
                        nodeIndex: i
                    };
                    pathSegment.nodes.push(node);
                }
                
                cemeteryPaths.push(pathSegment);
            });
        }
    });
}

/**
 * Build graph structure for A* pathfinding algorithm
 */
function buildPathGraph() {
    pathGraph.clear();
    
    // Add all nodes to the graph
    cemeteryPaths.forEach(path => {
        path.nodes.forEach(node => {
            pathGraph.set(node.id, {
                ...node,
                connections: []
            });
        });
    });
    
    // Connect adjacent nodes within same path
    cemeteryPaths.forEach(path => {
        for (let i = 0; i < path.nodes.length - 1; i++) {
            const currentNode = pathGraph.get(path.nodes[i].id);
            const nextNode = pathGraph.get(path.nodes[i + 1].id);
            
            const distance = calculateDistance(
                currentNode.lat, currentNode.lng,
                nextNode.lat, nextNode.lng
            );
            
            // Bidirectional connection
            currentNode.connections.push({ nodeId: nextNode.id, distance });
            nextNode.connections.push({ nodeId: currentNode.id, distance });
        }
    });
    
    // Connect intersecting paths (nodes that are very close to each other)
    const nodes = Array.from(pathGraph.values());
    for (let i = 0; i < nodes.length; i++) {
        for (let j = i + 1; j < nodes.length; j++) {
            const node1 = nodes[i];
            const node2 = nodes[j];
            
            // Skip if same path
            if (node1.pathId === node2.pathId) continue;
            
            const distance = calculateDistance(
                node1.lat, node1.lng,
                node2.lat, node2.lng
            );
            
            // Connect if nodes are very close (within 2 meters)
            if (distance < 0.000018) { // Approximately 2 meters in decimal degrees
                node1.connections.push({ nodeId: node2.id, distance });
                node2.connections.push({ nodeId: node1.id, distance });
            }
        }
    }
}

/**
 * Check if coordinates are within cemetery bounds
 */
function isWithinCemeteryBounds(lat, lng) {
    return cemeteryBounds && cemeteryBounds.contains([lat, lng]);
}

/**
 * Calculate distance between two points using Haversine formula
 */
function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371e3; // Earth radius in meters
    const φ1 = lat1 * Math.PI / 180;
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lng2 - lng1) * Math.PI / 180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
              Math.cos(φ1) * Math.cos(φ2) *
              Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c; // Distance in meters
}

/**
 * Find nearest path node to given coordinates
 */
function findNearestPathNode(lat, lng) {
    let nearestNode = null;
    let minDistance = Infinity;
    
    pathGraph.forEach(node => {
        const distance = calculateDistance(lat, lng, node.lat, node.lng);
        if (distance < minDistance) {
            minDistance = distance;
            nearestNode = node;
        }
    });
    
    return { node: nearestNode, distance: minDistance };
}

/**
 * A* pathfinding algorithm for cemetery paths
 */
function findPathAStar(startNodeId, endNodeId) {
    const openSet = new Set([startNodeId]);
    const cameFrom = new Map();
    const gScore = new Map();
    const fScore = new Map();
    
    // Initialize scores
    pathGraph.forEach((node, nodeId) => {
        gScore.set(nodeId, Infinity);
        fScore.set(nodeId, Infinity);
    });
    
    gScore.set(startNodeId, 0);
    const startNode = pathGraph.get(startNodeId);
    const endNode = pathGraph.get(endNodeId);
    
    fScore.set(startNodeId, calculateDistance(
        startNode.lat, startNode.lng,
        endNode.lat, endNode.lng
    ));
    
    while (openSet.size > 0) {
        // Get node with lowest fScore
        let current = null;
        let lowestFScore = Infinity;
        
        openSet.forEach(nodeId => {
            const score = fScore.get(nodeId);
            if (score < lowestFScore) {
                lowestFScore = score;
                current = nodeId;
            }
        });
        
        if (current === endNodeId) {
            // Reconstruct path
            const path = [];
            let currentNodeId = current;
            
            while (currentNodeId) {
                const node = pathGraph.get(currentNodeId);
                path.unshift([node.lat, node.lng]);
                currentNodeId = cameFrom.get(currentNodeId);
            }
            
            return path;
        }
        
        openSet.delete(current);
        const currentNode = pathGraph.get(current);
        
        currentNode.connections.forEach(connection => {
            const neighbor = connection.nodeId;
            const tentativeGScore = gScore.get(current) + connection.distance;
            
            if (tentativeGScore < gScore.get(neighbor)) {
                cameFrom.set(neighbor, current);
                gScore.set(neighbor, tentativeGScore);
                
                const neighborNode = pathGraph.get(neighbor);
                const heuristic = calculateDistance(
                    neighborNode.lat, neighborNode.lng,
                    endNode.lat, endNode.lng
                );
                
                fScore.set(neighbor, tentativeGScore + heuristic);
                openSet.add(neighbor);
            }
        });
    }
    
    return null; // No path found
}

var bounds_group = new L.featureGroup([]);

function setBounds() {}
map.createPane('pane_Esti_0');
map.getPane('pane_Esti_0').style.zIndex = 400;
var layer_Esti_0 = L.tileLayer(
    'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        pane: 'pane_Esti_0',
        opacity: 1.0,
        attribution: '',
        minZoom: 2,
        maxZoom: 28,
        minNativeZoom: 0,
        maxNativeZoom: 18
    });
layer_Esti_0;
map.addLayer(layer_Esti_0);

function pop_Chapel_1(feature, layer) {
    layer.on({
        mouseout: function(e) {
            if (typeof layer.closePopup == 'function') {
                layer.closePopup();
            } else {
                layer.eachLayer(function(feature) {
                    feature.closePopup()
                });
            }
        },
        mouseover: highlightFeature,
    });
    var popupContent = '<table>\
                        <tr>\
                            <td colspan="2">' + (feature.properties['Chapel'] !== null ? autolinker.link(String(feature
        .properties['Chapel']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                        </tr>\
                    </table>';
    var content = removeEmptyRowsFromPopupContent(popupContent, feature);
    layer.on('popupopen', function(e) {
        addClassToPopupIfMedia(content, e.popup);
    });
    layer.bindPopup(content, {
        maxHeight: 400
    });
}

function style_Chapel_1_0() {
    return {
        pane: 'pane_Chapel_1',
        rotationAngle: 0.7155853000000001,
        rotationOrigin: 'center center',
        icon: L.icon({
            iconUrl: 'markers/Chapel_1.svg',
            iconSize: [27.36, 27.36]
        }),
        interactive: true,
    }
}
map.createPane('pane_Chapel_1');
map.getPane('pane_Chapel_1').style.zIndex = 401;
map.getPane('pane_Chapel_1').style['mix-blend-mode'] = 'normal';
var layer_Chapel_1 = new L.geoJson(json_Chapel_1, {
    attribution: '',
    interactive: true,
    dataVar: 'json_Chapel_1',
    layerName: 'layer_Chapel_1',
    pane: 'pane_Chapel_1',
    onEachFeature: pop_Chapel_1,
    pointToLayer: function(feature, latlng) {
        var context = {
            feature: feature,
            variables: {}
        };
        return L.marker(latlng, style_Chapel_1_0(feature));
    },
});
bounds_group.addLayer(layer_Chapel_1);
map.addLayer(layer_Chapel_1);

function pop_parking_2(feature, layer) {
    layer.on({
        mouseout: function(e) {
            if (typeof layer.closePopup == 'function') {
                layer.closePopup();
            } else {
                layer.eachLayer(function(feature) {
                    feature.closePopup()
                });
            }
        },
        mouseover: highlightFeature,
    });
    var popupContent = '<table>\
                        <tr>\
                            <td colspan="2">' + (feature.properties['parking'] !== null ? autolinker.link(String(
        feature
        .properties['parking']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                        </tr>\
                    </table>';
    var content = removeEmptyRowsFromPopupContent(popupContent, feature);
    layer.on('popupopen', function(e) {
        addClassToPopupIfMedia(content, e.popup);
    });
    layer.bindPopup(content, {
        maxHeight: 400
    });
}

function style_parking_2_0() {
    return {
        pane: 'pane_parking_2',
        rotationAngle: 0.0,
        rotationOrigin: 'center center',
        icon: L.icon({
            iconUrl: 'markers/parking_2.svg',
            iconSize: [27.36, 27.36]
        }),
        interactive: true,
    }
}
map.createPane('pane_parking_2');
map.getPane('pane_parking_2').style.zIndex = 402;
map.getPane('pane_parking_2').style['mix-blend-mode'] = 'normal';
var layer_parking_2 = new L.geoJson(json_parking_2, {
    attribution: '',
    interactive: true,
    dataVar: 'json_parking_2',
    layerName: 'layer_parking_2',
    pane: 'pane_parking_2',
    onEachFeature: pop_parking_2,
    pointToLayer: function(feature, latlng) {
        var context = {
            feature: feature,
            variables: {}
        };
        return L.marker(latlng, style_parking_2_0(feature));
    },
});
bounds_group.addLayer(layer_parking_2);
map.addLayer(layer_parking_2);

function pop_Clusters_3(feature, layer) {
    /*
        Uncomment the following lines if to add 
        hover to pop-up the dialog.
    */

    // layer.on({
    //     mouseout: function(e) {
    //         if (typeof layer.closePopup == 'function') {
    //             layer.closePopup();
    //         } else {
    //             layer.eachLayer(function(feature) {
    //                 feature.closePopup()
    //             });
    //         }
    //     },
    //     mouseover: highlightFeature,
    // });
    var popupContent = '<table>\
                        <tr>\
                            <td colspan="2">' + (feature.properties['Clusters'] !== null ? autolinker.link(String(
        feature
        .properties['Clusters']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                        </tr>\
                    </table>';
    var content = removeEmptyRowsFromPopupContent(popupContent, feature);
    layer.on('popupopen', function(e) {
        addClassToPopupIfMedia(content, e.popup);
    });
    layer.bindPopup(content, {
        maxHeight: 400
    });
}

function style_Clusters_3_0() {
    return {
        pane: 'pane_Clusters_3',
        opacity: 1,
        color: 'rgba(35,35,35,1.0)',
        dashArray: '',
        lineCap: 'butt',
        lineJoin: 'miter',
        weight: 1.0,
        fill: true,
        fillOpacity: 1,
        fillColor: 'rgba(243,166,178,0.3215686274509804)',
        interactive: true,
    }
}
map.createPane('pane_Clusters_3');
map.getPane('pane_Clusters_3').style.zIndex = 403;
map.getPane('pane_Clusters_3').style['mix-blend-mode'] = 'normal';
var layer_Clusters_3 = new L.geoJson(json_Clusters_3, {
    attribution: '',
    interactive: true,
    dataVar: 'json_Clusters_3',
    layerName: 'layer_Clusters_3',
    pane: 'pane_Clusters_3',
    onEachFeature: pop_Clusters_3,
    style: style_Clusters_3_0,
});
bounds_group.addLayer(layer_Clusters_3);
map.addLayer(layer_Clusters_3);

function pop_Taas_4(feature, layer) {
    layer.on({
        mouseout: function(e) {
            if (typeof layer.closePopup == 'function') {
                layer.closePopup();
            } else {
                layer.eachLayer(function(feature) {
                    feature.closePopup()
                });
            }
        },
        mouseover: highlightFeature,
    });
    var popupContent = '<table>\
                        <tr>\
                            <td colspan="2">' + (feature.properties['id'] !== null ? autolinker.link(String(feature
        .properties['id']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                        </tr>\
                    </table>';
    var content = removeEmptyRowsFromPopupContent(popupContent, feature);
    layer.on('popupopen', function(e) {
        addClassToPopupIfMedia(content, e.popup);
    });
    layer.bindPopup(content, {
        maxHeight: 400
    });
}

function style_Taas_4_0() {
    return {
        pane: 'pane_Taas_4',
        opacity: 1,
        color: 'rgba(178,0,0,1.0)',
        dashArray: '',
        lineCap: 'square',
        lineJoin: 'bevel',
        weight: 9.0,
        fillOpacity: 0,
        interactive: true,
    }
}
map.createPane('pane_Taas_4');
map.getPane('pane_Taas_4').style.zIndex = 404;
map.getPane('pane_Taas_4').style['mix-blend-mode'] = 'normal';
var layer_Taas_4 = new L.geoJson(json_Taas_4, {
    attribution: '',
    interactive: true,
    dataVar: 'json_Taas_4',
    layerName: 'layer_Taas_4',
    pane: 'pane_Taas_4',
    onEachFeature: pop_Taas_4,
    style: style_Taas_4_0,
});
bounds_group.addLayer(layer_Taas_4);
map.addLayer(layer_Taas_4);

function getButtons(feature) {
    return `
            <div class="buttons">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== null && $_SESSION['user_type'] != 'user') { ?>
                ${feature.properties['Status'] !== 'vacant' && feature.properties['Status'] !== 'reserved' ? `
                <span class="edit-record-btn">
                <a href="index.php?id=${feature.properties['id'] !== null ? feature.properties['id'] : ''}&page=edit_record">
                    <button type="button" class="btn btn-secondary btn-sm"  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Deceased Record">
                    <i class="bi bi-pencil-square"></i>
                    </button>
                </a>
                </span>
                ` : ''}
          
                 <span class="add-record-btn">
                <a href="index.php?stat=${feature.properties['Status'] !== null ? feature.properties['Status'] : ''}&graveno=${feature.properties['Grave No.'] !== null ? feature.properties['Grave No.'] : ''}&page=new_record">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New Deceased Record">
                    <i class="fas fa-plus"></i>
                    </button>
                </a>
                </span>  
                
                 ${(feature.properties['PhotoCount'] < 2 && feature.properties['Status'] !== 'vacant') ? `
                <span class="add-photo-btn">
                    <a href="index.php?graveid=${feature.properties["Grave No."]}&page=add_img">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Photo">
                        <i class="bi bi-image-fill"></i>
                    </button>
                    </a>
                </span>
                ` : ''}
                <?php } ?>
            </div>`;
}

function empty(feature) {
    return `
        <div class="popup-header">
            <div class="header-title">
                <span class="popup-title1">CemeterEase</span> <br>
                <span class="popup-title">Grave Details</span>
            </div>
        </div>
        <div class="info-grid">
            ${getButtons(feature)}
            ${getLocationSection(feature)}
        </div>

        <div class="timeline-grid">
          <center><strong>Empty</strong></center>
        </div> <br>
        <hr>
        ${getPhotosSection(feature)}`;
}

//Reserved Template
function getReservedTemplate(feature) {
    return `
        <div class="popup-header">
            <div class="header-title">
                <span class="popup-title1">CemeterEase</span> <br>
                <span class="popup-title">Grave Details</span>
            </div>
        </div>
        <div class="info-grid">
            ${getButtons(feature)}
            ${getLocationSection(feature)}
        </div>
        <div class="timeline-grid">
            <center><strong>Reserved</strong></center>
            <div class="icons">
                <i class="fas fa-user"></i>
                <span class="info-label">Lot Owner</span>
                <span id="name" class="info-value">${feature.properties['Contact Person'] ? autolinker.link(feature.properties['Contact Person'].toLocaleString().replace(/\b\w/g, c => c.toUpperCase())) : 'N/A'}</span>
            </div>
        </div>   
        <br>
        <hr>
        ${getPhotosSection(feature)}`;
}

function getMultipleGraveTemplate(feature) {
    return `
        <div class="popup-header">
            <div class="header-title">
                <span class="popup-title1">CemeterEase</span> <br>
                <span class="popup-title">Multiple Interments</span>
            </div>
        </div>
        <div class="info-grid">
            ${getButtons(feature)}
            ${getLocationSection(feature)}
        </div>

        <div class="timeline-grid"y>
            <div class="icons">
                <i class="fas fa-user"></i>
                <div class="values">
                    <span class="info-label">Interred Persons</span>
                    <!-- Display multiple records -->
                    <div id="recordContainer">${feature.properties['Multiple Names'] ? autolinker.link(feature.properties['Multiple Names'].toLocaleString().replace(/\b\w/g, c => c.toUpperCase())) : 'N/A'}</div>
                </div>
            </div>
        </div>   
        ${getPhotosSection(feature)}`;
}


function getSingleGraveTemplate(feature) {
    return `
        <div class="popup-header">
            <div class="header-title">
                <span class="popup-title1">CemeterEase</span> <br>
                <span class="popup-title">Interment Details</span>
            </div>
        </div>
        <div class="info-grid">
            ${getButtons(feature)}
            <div class="icons">
                <i class="fas fa-user"></i>
                <div class="values">
                    <span class="info-label">Name</span><br>
                    <span id="name" class="info-value">${feature.properties['Name'] ? autolinker.link(feature.properties['Name'].toLocaleString().replace(/\b\w/g, c => c.toUpperCase())) : 'N/A'}</span>
                </div>
            </div>
            ${getLocationSection(feature)}
        </div>
        ${getTimelineSection(feature)}
        ${getPhotosSection(feature)}`;
}

function getLocationSection(feature) {
    return `
        <div class="icons">
            <i class="fas fa-map-pin"></i>
            <div class="values">
                <span class="info-label">Location</span> <br>
                <span id="block" class="info-value">Block ${feature.properties['Block'] || 'N/A'} • Grave ${feature.properties['Grave No.'] || 'N/A'}</span>
            </div>
            <div class="navigation-section">
                <a onclick="navigateToGrave(${feature.geometry.coordinates[1]}, ${feature.geometry.coordinates[0]})" class="direction-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#ffffff" d="M502.6 233.3L278.7 9.4c-12.5-12.5-32.8-12.5-45.4 0L9.4 233.3c-12.5 12.5-12.5 32.8 0 45.4l223.9 223.9c12.5 12.5 32.8 12.5 45.4 0l223.9-223.9c12.5-12.5 12.5-32.8 0-45.4zm-101 12.6l-84.2 77.7c-5.1 4.7-13.4 1.1-13.4-5.9V264h-96v64c0 4.4-3.6 8-8 8h-32c-4.4 0-8-3.6-8-8v-80c0-17.7 14.3-32 32-32h112v-53.7c0-7 8.3-10.6 13.4-5.9l84.2 77.7c3.4 3.2 3.4 8.6 0 11.8z"/></svg>
                </a>
            </div>
        </div>`;
}

function getTimelineSection(feature) {
    return `
        <div class="timeline-grid">
            <div class="icons">
                <i class="fas fa-calendar"></i>
                <span class="timeline-title">Timeline</span>
            </div>
            <div class="container-map">
                <div class="date-left">
                    <span class="info-label">Birth</span> <br>
                    <span id="birthDate" class="info-value">${feature.properties['Birth'] || 'N/A'}</span>
                </div>
                <div class="date-right">
                    <span class="info-label">Death</span> <br>
                    <span id="deathDate" class="info-value">${feature.properties['Death'] || 'N/A'}</span>
                </div>
            </div>
            <div class="seperator"></div>
            <div class="since">
                <span class="info-label">Time since Death</span> <br>
                <span id="timeSinceDeath" class="info-value">${feature.properties['Years Buried'] || 'N/A'}</span>
            </div>
        </div>`;
}

function getPhotosSection(feature) {
    return `
        <div class="images-container" style="width: 100%;">
            ${feature.properties['PhotoCount'] > 0 
                ? `<div class="images-grid">${feature.properties['Photos']}</div>`
                : '<div style="display: flex; justify-content: center;"><br>No photos available</div>'}
        </div>`;
}

function pop_category_5(feature, layer) {

    /*
        Uncomment the following lines if to add 
        hover to pop-up the dialog.
    */

    // layer.on({
    //     mouseout: function(e) {
    //         if (typeof layer.closePopup == 'function') {
    //             layer.closePopup();
    //         } else {
    //             layer.eachLayer(function(feature) {
    //                 feature.closePopup()
    //             });
    //         }
    //     },
    //     mouseover: highlightFeature,
    // });

    const deceasedCount = feature.properties['DeceasedCount'] || 0;
    const graveStatus = feature.properties['Status'];
    const visibility = feature.properties['Visibility'];

    const popupContent =
        //Display empty template if visibility is private
        (visibility === 'private') ? empty(feature) :
        //Display reserved template if grave status is 'reserved'
        (graveStatus === 'reserved') ? getReservedTemplate(feature) :
        //Display empty template if the deceased count is less than 1 or the grave status is vacant
        (deceasedCount < 1 || graveStatus === 'vacant') ? empty(feature) :
        //Display multiple grave template if the deceased count is greater than 1
        (deceasedCount > 1) ?
        getMultipleGraveTemplate(feature) :
        getSingleGraveTemplate(feature);
    // Only add navigation scripts once
    if (!window.navigationScriptAdded) {
        addNavigationScript();
        window.navigationScriptAdded = true;
    }
    if (!window.navigationStylesAdded) {
        addStyles();
        window.navigationStylesAdded = true;
    }

    const content = removeEmptyRowsFromPopupContent(popupContent, feature);
    layer.on('popupopen', (e) => addClassToPopupIfMedia(content, e.popup));
    layer.bindPopup(content, {
        maxHeight: 1000,
        minHeight: 1000,
        maxWidth: 400
    });
}

function addNavigationScript() {
    const script = document.createElement('script');
    script.textContent = `
        /**
         * Custom cemetery routing engine
         * Uses path_1.js data for accurate navigation within cemetery grounds
         */
        
        /**
         * Create custom route using cemetery paths
         */
        function createCustomRoute(userLat, userLng, destLat, destLng) {
            try {
                // Find nearest path nodes for start and end points
                const startResult = findNearestPathNode(userLat, userLng);
                const endResult = findNearestPathNode(destLat, destLng);
                
                if (!startResult.node || !endResult.node) {
                    console.warn('Could not find suitable path nodes');
                    return null;
                }
                
                // Check if we need to walk to the path first
                const walkToStart = startResult.distance > 5; // More than 5 meters from path
                const walkFromEnd = endResult.distance > 5;
                
                // Find optimal path using A* algorithm
                const pathCoordinates = findPathAStar(startResult.node.id, endResult.node.id);
                
                if (!pathCoordinates) {
                    console.warn('No path found between nodes');
                    return null;
                }
                
                // Build complete route coordinates
                let routeCoords = [];
                
                // Add walk to start path if needed
                if (walkToStart) {
                    routeCoords.push([userLat, userLng]);
                }
                
                // Add the actual path
                routeCoords = routeCoords.concat(pathCoordinates);
                
                // Add walk from end path if needed
                if (walkFromEnd) {
                    routeCoords.push([destLat, destLng]);
                }
                
                return {
                    coordinates: routeCoords,
                    distance: calculateRouteDistance(routeCoords),
                    isCustomRoute: true,
                    walkToStart,
                    walkFromEnd
                };
                
            } catch (error) {
                console.error('Error creating custom route:', error);
                return null;
            }
        }
        
        /**
         * Calculate total route distance
         */
        function calculateRouteDistance(coordinates) {
            let totalDistance = 0;
            for (let i = 0; i < coordinates.length - 1; i++) {
                const [lat1, lng1] = coordinates[i];
                const [lat2, lng2] = coordinates[i + 1];
                totalDistance += calculateDistance(lat1, lng1, lat2, lng2);
            }
            return totalDistance;
        }
        
        /**
         * Create custom routing control for cemetery paths
         */
        function createCustomRoutingControl(routeData, userLat, userLng, destLat, destLng) {
            // Create a custom control that mimics Leaflet Routing Machine
            const customControl = L.Control.extend({
                options: {
                    position: 'topright'
                },
                
                onAdd: function(map) {
                    // Create control container (hidden)
                    const container = L.DomUtil.create('div', 'custom-routing-control');
                    container.style.display = 'none';
                    return container;
                },
                
                onRemove: function(map) {
                    if (this._routeLine) {
                        map.removeLayer(this._routeLine);
                    }
                    if (this._startMarker) {
                        map.removeLayer(this._startMarker);
                    }
                    if (this._endMarker) {
                        map.removeLayer(this._endMarker);
                    }
                }
            });
            
            const control = new customControl();
            
            // Add route line to map
            control._routeLine = L.polyline(routeData.coordinates, {
                color: '#435ebe',
                weight: 6,
                opacity: 0.8,
                dashArray: routeData.isCustomRoute ? '10,5' : '',
                className: 'cemetery-route-line'
            }).addTo(map);
            
            // Add start marker
            control._startMarker = L.marker([userLat, userLng], {
                icon: createUserIcon()
            }).addTo(map);
            control._startMarker.bindPopup('Your Current Location');
            userLocationMarker = control._startMarker;
            
            // Add end marker
            control._endMarker = L.marker([destLat, destLng], {
                icon: createDestinationIcon()
            }).addTo(map);
            control._endMarker.bindPopup('Destination: Your Selected Grave');
            
            // Simulate routing events for compatibility
            setTimeout(() => {
                const fakeEvent = {
                    routes: [{
                        summary: {
                            totalDistance: routeData.distance,
                            totalTime: Math.round(routeData.distance / 1.4) // Walking speed: ~1.4 m/s
                        }
                    }]
                };
                
                // Trigger custom events for route recalculation
                control.fire('routesfound', fakeEvent);
                control.fire('routefound', fakeEvent.routes[0]);
                
                // Show the control
                const container = control.getContainer();
                container.style.display = 'block';
                
            }, 100);
        }
        
        /**
         * Create user icon for map
         */
        function createUserIcon() {
            return L.divIcon({
                className: 'custom-user-marker',
                html: '<div class="user-marker"><i class="fas fa-user"></i></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
        }
        
        /**
         * Create destination icon for map
         */
        function createDestinationIcon() {
            return L.divIcon({
                className: 'custom-destination-marker',
                html: '<div class="destination-marker"><i class="fas fa-flag"></i></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
        }
    `;
    document.head.appendChild(script);
}

function addStyles() {
    const style = document.createElement('style');
    style.textContent = `
            /* Original direction icon styles */
            .direction-icon {
                background: #435ebe;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                transition: all 0.3s ease;
                margin-left: 30px;
            }
          
            .direction-icon svg {
                width: 20px;
                height: 20px;
            }
            
            .direction-icon:hover {
                background: #0056b3;
                transform: scale(1.1);
            }

            /* Custom user location marker */
            .custom-user-marker .user-marker {
                background: #4CAF50;
                border: 3px solid #fff;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                animation: pulse 2s infinite;
            }

            .custom-user-marker .user-marker i {
                color: white;
                font-size: 14px;
            }

            /* Custom destination marker */
            .custom-destination-marker .destination-marker {
                background: #f44336;
                border: 2px solid #fff;
                border-radius: 50% 50% 50% 0;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                transform: rotate(-45deg);
            }

            .custom-destination-marker .destination-marker i {
                color: white;
                font-size: 16px;
                transform: rotate(45deg);
            }

            /* Navigation info panel */
            .navigation-info-panel {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.15);
                z-index: 1000;
                min-width: 280px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                overflow: hidden;
                border: 1px solid #e0e0e0;
            }

            .nav-header {
                background: linear-gradient(135deg, #435ebe, #5a6fd8);
                color: white;
                padding: 16px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-weight: 600;
                font-size: 16px;
            }

            .nav-header i {
                margin-right: 10px;
                font-size: 18px;
            }

            .stop-nav-btn {
                background: rgba(255,255,255,0.2);
                border: none;
                border-radius: 6px;
                color: white;
                padding: 8px 10px;
                cursor: pointer;
                transition: background 0.3s ease;
                font-size: 14px;
            }

            .stop-nav-btn:hover {
                background: rgba(255,255,255,0.3);
            }

            .nav-details {
                padding: 20px;
                display: flex;
                justify-content: space-around;
                background: #fafafa;
            }

            .nav-stat {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .nav-stat i {
                font-size: 24px;
                color: #435ebe;
                margin-bottom: 8px;
            }

            .nav-stat span {
                font-size: 14px;
                font-weight: 600;
                color: #333;
            }

            /* Route type indicator */
            .route-type {
                padding: 12px 20px;
                background: #f8f9fa;
                border-top: 1px solid #e0e0e0;
                display: flex;
                align-items: center;
                font-size: 14px;
                color: #666;
            }

            .route-type i {
                margin-right: 8px;
                color: #435ebe;
            }

            /* Cemetery route styling */
            .cemetery-route-line {
                z-index: 1000;
            }

            /* Pulse animation for user marker */
            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
                }
                70% {
                    box-shadow: 0 0 0 10px rgba(76, 175, 80, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
                }
            }

            /* Mobile responsive adjustments */
            @media (max-width: 768px) {
                .navigation-info-panel {
                    top: 10px;
                    right: 10px;
                    left: 10px;
                    min-width: auto;
                }
                
                .nav-details {
                    padding: 15px;
                }
                
                .nav-header {
                    padding: 12px 15px;
                    font-size: 14px;
                }
                
                .route-type {
                    padding: 10px 15px;
                }
            }

            /* Accessibility improvements */
            .direction-icon:focus,
            .stop-nav-btn:focus {
                outline: 2px solid #435ebe;
                outline-offset: 2px;
            }

            /* High contrast mode support */
            @media (prefers-contrast: high) {
                .navigation-info-panel {
                    border: 2px solid #000;
                }
                
                .nav-header {
                    background: #000;
                }
            }
    `;
    document.head.appendChild(style);
}

// Initialize cemetery routing system when map loads
map.whenReady(function() {
    // Initialize cemetery routing after a short delay to ensure all layers are loaded
    setTimeout(function() {
        initializeCemeteryRouting();
        console.log('Cemetery-aware navigation system initialized');
        
        // Add cemetery boundary visualization (optional - for debugging)
        if (typeof window.DEBUG_MODE !== 'undefined' && window.DEBUG_MODE) {
            const boundaryRect = L.rectangle(cemeteryBounds, {
                color: "#ff7800",
                weight: 2,
                opacity: 0.5,
                fillOpacity: 0.1
            }).addTo(map);
            boundaryRect.bindPopup("Cemetery Boundary - Custom routing area");
        }
        
        // Add console logging for development
        console.log('Cemetery bounds:', cemeteryBounds ? cemeteryBounds.toBBoxString() : 'Not defined');
        console.log('Path segments loaded:', cemeteryPaths.length);
        console.log('Path nodes in graph:', pathGraph.size);
        
    }, 1000);
});

// Error handling for missing path data
if (typeof json_path_1 === 'undefined') {
    console.warn('Cemetery path data (json_path_1) not found. Custom routing will be unavailable.');
    
    // Show user-friendly message if they try to navigate
    const originalNavigateFunction = window.navigateToGrave;
    window.navigateToGrave = function(lat, lng) {
        console.log('Falling back to external routing due to missing path data');
        if (originalNavigateFunction) {
            originalNavigateFunction(lat, lng);
        }
    };
}

// Performance monitoring for routing
window.cemeteryRoutingStats = {
    customRoutesCreated: 0,
    externalRoutesCreated: 0,
    routingErrors: 0,
    
    logRoute: function(type, success = true) {
        if (success) {
            if (type === 'custom') {
                this.customRoutesCreated++;
            } else {
                this.externalRoutesCreated++;
            }
        } else {
            this.routingErrors++;
        }
        
        console.log('Routing stats:', {
            custom: this.customRoutesCreated,
            external: this.externalRoutesCreated,
            errors: this.routingErrors
        });
    }
};

</script>