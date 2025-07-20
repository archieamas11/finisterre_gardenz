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

function getHeader(feature) {
    const cat = String(feature.properties['category']).toLowerCase();
    console.log(cat);
    const cssClass =
        cat === 'platinum' ? 'platinum' :
        cat === 'bronze' ? 'bronze' :
        cat === 'silver' ? 'silver' :
        cat === 'diamond' ? 'diamond' :
        '';

    return `
    <div class="popup-header ${cssClass}">
      <div class="header-title">
        <span class="popup-title1">Finisterre</span><br>
        <span class="popup-title">Plot Details</span>
      </div>
    </div>
  `;
}

function empty(feature) {
    return `
        ${getHeader(feature)}
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
${getHeader(feature)}
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
        ${getHeader(feature)}
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
        ${getHeader(feature)}
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
    const cat = String(feature.properties['category']).toLowerCase();
    console.log(cat);
    const cssClass =
        cat === 'platinum' ? 'platinum' :
        cat === 'bronze' ? 'bronze' :
        cat === 'silver' ? 'silver' :
        cat === 'diamond' ? 'diamond' :
        '';
    return `
        <div class="icons">
            <i class="fas fa-map-pin"></i>
            <div class="values">
                <span class="info-label">Location</span> <br>
                <span id="block" class="info-value">Block ${feature.properties['Block'] || 'N/A'} • Grave ${feature.properties['Grave No.'] || 'N/A'}</span>
            </div>
            <div class="navigation-section">
                <a onclick="navigateToGrave(${feature.geometry.coordinates[1]}, ${feature.geometry.coordinates[0]})" class="direction-icon ${cssClass}">
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
    addNavigationScript();
    addStyles();

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
         * Cemetery-aware grave navigation system
         * Automatically uses custom paths when routing within cemetery boundaries
         */
        window.navigateToGrave = function(lat, lng) {
            console.log('🚀 Starting navigation to grave at:', [lat, lng]);
            
            // Check if geolocation is supported
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser. Please enable GPS to use navigation.');
                return;
            }

            // Store destination coordinates
            destinationCoords = [lat, lng];
            
            // Clear any existing route
            if (currentRoute) {
                if (currentRoute._container) {
                    map.removeControl(currentRoute);
                } else if (currentRoute.routeLine) {
                    map.removeLayer(currentRoute.routeLine);
                    if (currentRoute.userMarker) map.removeLayer(currentRoute.userMarker);
                    if (currentRoute.destMarker) map.removeLayer(currentRoute.destMarker);
                }
                currentRoute = null;
            }

            // Get current position and create intelligent route
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    
                    console.log('📍 User location:', [userLat, userLng]);
                    
                    // Check if both user and destination are within cemetery
                    const userInCemetery = CemeteryPathfinder.isWithinCemetery(userLat, userLng);
                    const destInCemetery = CemeteryPathfinder.isWithinCemetery(lat, lng);
                    
                    console.log('🏞️ Cemetery routing check - User:', userInCemetery, 'Destination:', destInCemetery);
                    
                    if (userInCemetery && destInCemetery) {
                        // Both points are in cemetery - use custom pathfinding
                        createCemeteryRoute(userLat, userLng, lat, lng);
                    } else {
                        // Use external routing (fallback to Leaflet Routing Machine)
                        createExternalRoute(userLat, userLng, lat, lng);
                    }
                },
                function(error) {
                    let errorMessage = 'Error getting your location: ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Location access denied. Please enable GPS and refresh the page.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information unavailable. Please check your GPS signal.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out. Please try again.';
                            break;
                        default:
                            errorMessage += 'An unknown error occurred.';
                            break;
                    }
                    alert(errorMessage);
                    console.error('Geolocation error:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                }
            );
        };

        /**
         * Create cemetery-specific route using custom pathfinding
         */
        function createCemeteryRoute(userLat, userLng, destLat, destLng) {
            console.log('🏛️ Creating cemetery route using custom paths...');
            
            // Find path using cemetery pathfinder
            const cemeteryPath = CemeteryPathfinder.findPath(userLat, userLng, destLat, destLng);
            
            if (cemeteryPath && cemeteryPath.length > 0) {
                console.log('✅ Cemetery path found with', cemeteryPath.length, 'waypoints');
                
                // Create custom route visualization
                const routeLine = L.polyline(cemeteryPath, {
                    color: 'rgb(19, 86, 254)',           // Pink color matching your path style
                    weight: 10,
                    opacity: 0.9,
                    dashArray: '',         // Dashed line to show it's a custom route
                    className: 'cemetery-route-line'
                }).addTo(map);

                // Add user marker
                const userMarker = L.marker([userLat, userLng], {
                    icon: createUserIcon()
                }).addTo(map);
                userMarker.bindPopup('🚶 Your Current Location');
                userLocationMarker = userMarker;

                // Add destination marker
                const destMarker = L.marker([destLat, destLng], {
                    icon: createDestinationIcon()
                }).addTo(map);
                destMarker.bindPopup('🎯 Your Selected Grave');

                // Store route components for cleanup
                currentRoute = {
                    routeLine: routeLine,
                    userMarker: userMarker,
                    destMarker: destMarker,
                    isCustomRoute: true
                };

                // Calculate route stats
                const totalDistance = calculatePathDistance(cemeteryPath);
                const estimatedTime = Math.round((totalDistance * 1000) / 1.4 / 60); // 1.4 m/s walking speed

                // Show navigation info
                showNavigationInfo({
                    totalDistance: totalDistance * 1000, // Convert to meters
                    totalTime: estimatedTime * 60        // Convert to seconds
                });

                // Fit map to show entire route
                const group = L.featureGroup([userMarker, destMarker, routeLine]);
                map.fitBounds(group.getBounds().pad(0.1));

                isNavigating = true;
                setupLiveTracking();
                
                console.log('🎉 Cemetery navigation activated!');
                
            } else {
                console.warn('❌ Could not find cemetery path, falling back to external routing');
                // Fallback to external routing
                createExternalRoute(userLat, userLng, destLat, destLng);
            }
        }

        /**
         * Create external route using Leaflet Routing Machine (fallback)
         */
        function createExternalRoute(userLat, userLng, destLat, destLng) {
            console.log('🌐 Creating external route using Leaflet Routing Machine...');
            
            // Create routing control with custom styling
            currentRoute = L.Routing.control({
                waypoints: [
                    L.latLng(userLat, userLng),
                    L.latLng(destLat, destLng)
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                createMarker: function(i, waypoint, n) {
                    const marker = L.marker(waypoint.latLng, {
                        icon: i === 0 ? createUserIcon() : createDestinationIcon()
                    });
                    
                    if (i === 0) {
                        userLocationMarker = marker;
                        marker.bindPopup('🚶 Your Current Location');
                    } else {
                        marker.bindPopup('🎯 Your Selected Grave');
                    }
                    
                    return marker;
                },
                lineOptions: {
                    styles: [{
                        color: '#435ebe',
                        weight: 6,
                        opacity: 0.8
                    }]
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1'
                }),
                fitSelectedRoutes: true,
                show: false,
                collapsible: true
            }).addTo(map);

            // Handle routing events
            currentRoute.on('routesfound', function(e) {
                const routes = e.routes;
                const summary = routes[0].summary;
                
                // Show navigation info
                showNavigationInfo(summary);
                
                // Fit map to show entire route
                const group = new L.featureGroup([
                    L.marker([userLat, userLng]),
                    L.marker([destLat, destLng])
                ]);
                map.fitBounds(group.getBounds().pad(0.1));
            });

            currentRoute.on('routingerror', function(e) {
                alert('Could not find a route. Please try walking to the nearest path.');
                console.error('Routing error:', e.error);
            });

            isNavigating = true;
            setupLiveTracking();
        }

        /**
         * Calculate total distance of a path array
         */
        function calculatePathDistance(pathCoordinates) {
            let totalDistance = 0;
            for (let i = 0; i < pathCoordinates.length - 1; i++) {
                const [lat1, lng1] = pathCoordinates[i];
                const [lat2, lng2] = pathCoordinates[i + 1];
                totalDistance += CemeteryPathfinder.calculateDistance(lat1, lng1, lat2, lng2);
            }
            // Convert from degrees to approximate kilometers (rough conversion)
            return totalDistance * 111; // 1 degree ≈ 111 km
        }

        /**
         * Create custom user location icon
         */
        function createUserIcon() {
            return L.divIcon({
                html: '<div class="user-marker"><i class="fas fa-user"></i></div>',
                className: 'custom-user-marker',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        }

        /**
         * Create custom destination icon
         */
        function createDestinationIcon() {
            return L.divIcon({
                html: '<div class="destination-marker"><i class="fas fa-map-marker-alt"></i></div>',
                className: 'custom-destination-marker',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
        }

        /**
         * Setup live location tracking during navigation
         */
        function setupLiveTracking() {
            if (navigator.geolocation) {
                const watchId = navigator.geolocation.watchPosition(
                    function(position) {
                        if (isNavigating && userLocationMarker) {
                            const newLatLng = [position.coords.latitude, position.coords.longitude];
                            
                            // Update user marker position
                            userLocationMarker.setLatLng(newLatLng);
                            
                            // Update route if user has moved significantly
                            updateRouteIfNeeded(newLatLng);
                        }
                    },
                    function(error) {
                        console.warn('Live tracking error:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 3000
                    }
                );
                
                // Store watch ID for cleanup
                window.navigationWatchId = watchId;
            }
        }

        /**
         * Update route if user has moved significantly from the planned route
         */
        function updateRouteIfNeeded(userLatLng) {
            if (currentRoute && destinationCoords) {
                if (currentRoute.setWaypoints) {
                    // External route - update waypoints
                    const waypoints = currentRoute.getWaypoints();
                    waypoints[0].latLng = L.latLng(userLatLng[0], userLatLng[1]);
                    currentRoute.setWaypoints(waypoints);
                } else if (currentRoute.isCustomRoute) {
                    // Custom cemetery route - recreate if user moved significantly
                    const userInCemetery = CemeteryPathfinder.isWithinCemetery(userLatLng[0], userLatLng[1]);
                    const destInCemetery = CemeteryPathfinder.isWithinCemetery(destinationCoords[0], destinationCoords[1]);
                    
                    if (userInCemetery && destInCemetery) {
                        // Just update user marker position for now - don't constantly recreate route
                        if (currentRoute.userMarker) {
                            currentRoute.userMarker.setLatLng(userLatLng);
                        }
                    }
                }
            }
        }

        /**
         * Show navigation information panel
         */
        function showNavigationInfo(summary) {
            const distance = (summary.totalDistance / 1000).toFixed(2);
            const time = Math.round(summary.totalTime / 60);
            
            // Create or update navigation info panel
            let infoPanel = document.getElementById('navigation-info');
            if (!infoPanel) {
                infoPanel = document.createElement('div');
                infoPanel.id = 'navigation-info';
                infoPanel.className = 'navigation-info-panel';
                document.body.appendChild(infoPanel);
            }
            
            infoPanel.innerHTML = \`
                <div class="nav-header">
                    <i class="fas fa-route"></i>
                    <span>Navigation Active</span>
                    <button onclick="stopNavigation()" class="stop-nav-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="nav-details">
                    <div class="nav-stat">
                        <i class="fas fa-road"></i>
                        <span>\${distance} km</span>
                    </div>
                    <div class="nav-stat">
                        <i class="fas fa-walking"></i>
                        <span>\${time} min walk</span>
                    </div>
                </div>
            \`;
        }

        /**
         * Stop navigation and clean up
         */
        window.stopNavigation = function() {
            if (currentRoute) {
                if (currentRoute._container) {
                    // External route (Leaflet Routing Machine)
                    map.removeControl(currentRoute);
                } else if (currentRoute.routeLine) {
                    // Custom cemetery route
                    map.removeLayer(currentRoute.routeLine);
                    if (currentRoute.userMarker) map.removeLayer(currentRoute.userMarker);
                    if (currentRoute.destMarker) map.removeLayer(currentRoute.destMarker);
                }
                currentRoute = null;
            }
            
            if (window.navigationWatchId) {
                navigator.geolocation.clearWatch(window.navigationWatchId);
                window.navigationWatchId = null;
            }
            
            const infoPanel = document.getElementById('navigation-info');
            if (infoPanel) {
                infoPanel.remove();
            }
            
            isNavigating = false;
            userLocationMarker = null;
            destinationCoords = null;
            
            // Stop location control
            locateControl.stop();
        };
    `;
    document.head.appendChild(script);
}

function addStyles() {
    const style = document.createElement('style');
    style.textContent = `
            /* Original direction icon styles */
            .direction-icon {
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

            /* Custom routing control styles */
            .leaflet-routing-container {
                display: none; /* Hide default routing panel */
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

function style_category_5_0(feature) {
    switch (String(feature.properties['Status'])) {
        case 'occupied1':
            return {
                pane: 'pane_category_5',
                    shape: 'circle',
                    radius: 6.4,
                    opacity: 1,
                    color: 'rgba(0, 0, 0, 0.5)',
                    dashArray: '',
                    lineCap: 'butt',
                    lineJoin: 'miter',
                    weight: 2.0,
                    fill: true,
                    fillOpacity: 1,
                    fillColor: 'rgba(239,13,17,1.0)',
                    interactive: true,
            }
            break;
        case 'vacant':
            return {
                pane: 'pane_category_5',
                    shape: 'circle',
                    radius: 6.4,
                    opacity: 1,
                    color: 'rgba(0, 0, 0, 0.5)',
                    dashArray: '',
                    lineCap: 'butt',
                    lineJoin: 'miter',
                    weight: 2.0,
                    fill: true,
                    fillOpacity: 1,
                    fillColor: 'rgba(5,255,0,1)',
                    interactive: true,
            }
            break;
        case 'reserved':
            return {
                pane: 'pane_category_5',
                    shape: 'circle',
                    radius: 6.4,
                    opacity: 1,
                    color: 'rgba(0, 0, 0, 0.5)',
                    dashArray: '',
                    lineCap: 'butt',
                    lineJoin: 'miter',
                    weight: 2.0,
                    fill: true,
                    fillOpacity: 1,
                    fillColor: 'rgba(255,191,0,1.0)',
                    interactive: true,
            }
            break;
    }
}
map.createPane('pane_category_5');
map.getPane('pane_category_5').style.zIndex = 405;
map.getPane('pane_category_5').style['mix-blend-mode'] = 'normal';
var layer_category_5 = new L.geoJson(json_category_5, {
    attribution: '',
    interactive: true,
    dataVar: 'json_category_5',
    layerName: 'layer_category_5',
    pane: 'pane_category_5',
    onEachFeature: pop_category_5,
    pointToLayer: function(feature, latlng) {
        var context = {
            feature: feature,
            variables: {}
        };
        return L.shapeMarker(latlng, style_category_5_0(feature));
    },
});
bounds_group.addLayer(layer_category_5);
map.addLayer(layer_category_5);

function pop_entrance_6(feature, layer) {
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
                            <td colspan="2">' + (feature.properties['entrance'] !== null ? autolinker.link(String(
        feature
        .properties['entrance']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
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

function style_entrance_6_0() {
    return {
        pane: 'pane_entrance_6',
        rotationAngle: 0.0,
        rotationOrigin: 'center center',
        icon: L.icon({
            iconUrl: 'markers/entrance_6.svg',
            iconSize: [27.36, 27.36]
        }),
        interactive: true,
    }
}
map.createPane('pane_entrance_6');
map.getPane('pane_entrance_6').style.zIndex = 406;
map.getPane('pane_entrance_6').style['mix-blend-mode'] = 'normal';
var layer_entrance_6 = new L.geoJson(json_entrance_6, {
    attribution: '',
    interactive: true,
    dataVar: 'json_entrance_6',
    layerName: 'layer_entrance_6',
    pane: 'pane_entrance_6',
    onEachFeature: pop_entrance_6,
    pointToLayer: function(feature, latlng) {
        var context = {
            feature: feature,
            variables: {}
        };
        return L.marker(latlng, style_entrance_6_0(feature));
    },
});
bounds_group.addLayer(layer_entrance_6);
map.addLayer(layer_entrance_6);

function pop_exit_7(feature, layer) {
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
                            <td colspan="2">' + (feature.properties['exit'] !== null ? autolinker.link(String(feature
        .properties['exit']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
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

function style_exit_7_0() {
    return {
        pane: 'pane_exit_7',
        rotationAngle: 0.0,
        rotationOrigin: 'center center',
        icon: L.icon({
            iconUrl: 'markers/exit_7.svg',
            iconSize: [27.36, 27.36]
        }),
        interactive: true,
    }
}
map.createPane('pane_exit_7');
map.getPane('pane_exit_7').style.zIndex = 407;
map.getPane('pane_exit_7').style['mix-blend-mode'] = 'normal';
var layer_exit_7 = new L.geoJson(json_exit_7, {
    attribution: '',
    interactive: true,
    dataVar: 'json_exit_7',
    layerName: 'layer_exit_7',
    pane: 'pane_exit_7',
    onEachFeature: pop_exit_7,
    pointToLayer: function(feature, latlng) {
        var context = {
            feature: feature,
            variables: {}
        };
        return L.marker(latlng, style_exit_7_0(feature));
    },
});
bounds_group.addLayer(layer_exit_7);
map.addLayer(layer_exit_7);
var overlaysTree = [{
        label: '<img src="legend/exit_7.png" /> exit',
        layer: layer_exit_7
    },
    {
        label: '<img src="legend/entrance_6.png" /> entrance',
        layer: layer_entrance_6
    },
    {
        label: 'category<br /><table><tr><td style="text-align: center;"><img src="legend/category_5_0.png" /></td><td></td></tr><tr><td style="text-align: center;"><img src="legend/category_5_1.png" /></td><td></td></tr><tr><td style="text-align: center;"><img src="legend/category_5_2.png" /></td><td></td></tr><tr><td style="text-align: center;"><img src="legend/category_5_3.png" /></td><td></td></tr></table>',
        layer: layer_category_5
    },
    {
        label: '<img src="legend/Clusters_3.png" /> Clusters',
        layer: layer_Clusters_3
    },
    {
        label: '<img src="legend/parking_2.png" /> parking',
        layer: layer_parking_2
    },
    {
        label: '<img src="legend/Chapel_1.png" /> Chapel',
        layer: layer_Chapel_1
    },
    {
        label: "Esti",
        layer: layer_Esti_0
    },
]
var lay = L.control.layers.tree(null, overlaysTree, {
    //namedToggle: true,
    //selectorBack: false,
    //closedSymbol: '&#8862; &#x1f5c0;',
    //openedSymbol: '&#8863; &#x1f5c1;',
    //collapseAll: 'Collapse all',
    //expandAll: 'Expand all',
    collapsed: true,
});
lay.addTo(map);
setBounds();

// Animated Search Box
// Create animated search box using L.Control.Searchbox
const animatedSearchBox = L.control.searchbox({
    position: 'topright',
    expand: 'right',
    collapsed: false,
    width: '180px',
    iconPath: '../../webmap/images/search_icon.png',
    autocompleteFeatures: ['setValueOnClick']
});

// Add to map
map.addControl(animatedSearchBox);

// Add search functionality
animatedSearchBox.onInput('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();

    if (searchTerm.length < 2) {
        animatedSearchBox.clearItems();
        return;
    }

    const results = [];
    layer_pointsfinall_3.eachLayer(function(layer) {
        const props = layer.feature.properties;
        const visibility = String(props.Visibility).toLowerCase();
        const name = String(props.Name || '').toLowerCase();
        const deceasedName = String(props.deceased_fname || '').toLowerCase() + ' ' + String(props
            .deceased_lname || '').toLowerCase();

        // Filter based on visibility for normal users
        if (userType === 'user' && visibility !== 'public') {
            return;
        }

        // Check if search term matches name or deceased name
        if (name.includes(searchTerm) || deceasedName.includes(searchTerm)) {
            results.push({
                text: props.Name || 'Unnamed Grave',
                layer: layer
            });
        }
    });

    // Clear and add new results
    animatedSearchBox.clearItems();
    results.slice(0, 10).forEach(function(result) {
        animatedSearchBox.addItem(result.text);
    });
});

// Handle item selection
animatedSearchBox.onAutocomplete('click', function(e) {
    const selectedText = e.target.textContent;

    // Find the corresponding layer
    layer_pointsfinall_3.eachLayer(function(layer) {
        const props = layer.feature.properties;
        if (props.Name === selectedText) {
            const latlng = layer.getLatLng();
            map.setView(latlng, 28);
            layer.openPopup();
            animatedSearchBox.clearItems();
            // Clear the search input value after finding a match
            animatedSearchBox.setValue('');
            return;
        }
    });
});

// Handle search button click
animatedSearchBox.onButton('click', function() {
    const searchTerm = animatedSearchBox.getValue().toLowerCase();

    if (searchTerm.length < 2) return;

    // Find first matching result and navigate to it
    let found = false;
    layer_pointsfinall_3.eachLayer(function(layer) {
        if (found) return;

        const props = layer.feature.properties;
        const visibility = String(props.Visibility || '').toLowerCase();
        const name = String(props.Name || '').toLowerCase();
        const deceasedName = String(props.deceased_fname || '').toLowerCase() + ' ' + String(props
            .deceased_lname || '').toLowerCase();

        // Filter based on visibility for normal users
        if (userType === 'user' && visibility !== 'public') {
            return;
        }

        if (name.includes(searchTerm) || deceasedName.includes(searchTerm)) {
            const latlng = layer.getLatLng();
            map.setView(latlng, 28);
            layer.openPopup();
            // Clear the search input value after finding a match
            animatedSearchBox.setValue('');
            animatedSearchBox.clearItems();
            found = true;
        }
    });
});

// Move to search container if it exists
if (document.getElementById('search-container')) {
    const searchContainer = document.getElementById('search-container');
    const searchElement = document.querySelector('.leaflet-searchbox-container');
    if (searchElement) {
        searchContainer.appendChild(searchElement);
    }
}

// Set placeholder text for the search input
setTimeout(function() {
    const searchInput = document.querySelector('.leaflet-searchbox');
    if (searchInput) {
        searchInput.placeholder = 'Search grave records...';
    }
}, 100);


// set custom path for navigation guide
function pop_path_1(feature, layer) {
    var popupContent = '<table>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['path'] !== null ? autolinker.link(String(feature
        .properties['path']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
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

function style_path_1_0() {
    return {
        pane: 'pane_path_1',
        opacity: 1,
        color: 'rgb(19, 86, 254)',
        dashArray: '',
        lineCap: 'round',
        lineJoin: 'bevel',
        weight: 0,
        fillOpacity: 1,
        interactive: true,
    }
}
map.createPane('pane_path_1');
map.getPane('pane_path_1').style.zIndex = 401;
map.getPane('pane_path_1').style['mix-blend-mode'] = 'normal';
var layer_path_1 = new L.geoJson(json_path_1, {
    attribution: '',
    interactive: false,
    dataVar: 'json_path_1',
    layerName: 'layer_path_1',
    pane: 'pane_path_1',
    onEachFeature: pop_path_1,
    style: style_path_1_0,
});
bounds_group.addLayer(layer_path_1);
map.addLayer(layer_path_1);


// Cemetery boundaries (based on your coordinate data)
var cemeteryBounds = {
    north: 10.249302749341647,
    south: 10.247883800064669,
    east: 123.7988598710129,
    west: 123.79691285546676
};

/**
 * Custom pathfinding system for cemetery internal navigation
 * Uses the actual cemetery paths from json_path_1 data
 */
var CemeteryPathfinder = {
    // Convert cemetery paths to a graph structure for pathfinding
    pathGraph: null,
    nodes: [],
    edges: [],

    /**
     * Initialize the pathfinder by converting path data to a graph
     */
    initialize: function() {
        console.log('🗺️ Initializing cemetery pathfinder...');
        this.buildGraphFromPaths();
    },

    /**
     * Check if a coordinate is within cemetery boundaries
     */
    isWithinCemetery: function(lat, lng) {
        return lat >= cemeteryBounds.south &&
            lat <= cemeteryBounds.north &&
            lng >= cemeteryBounds.west &&
            lng <= cemeteryBounds.east;
    },

    /**
     * Build a graph structure from the cemetery path data
     */
    buildGraphFromPaths: function() {
        console.log('🔗 Building path graph from cemetery data...');
        this.nodes = [];
        this.edges = [];
        const tolerance = 5; // 5 meters tolerance for merging nearby nodes
        const nodeMap = new Map(); // Track node locations to merge duplicates

        // Extract all coordinates from path segments and merge nearby nodes
        json_path_1.features.forEach((feature, featureIndex) => {
            if (feature.geometry.type === 'MultiLineString') {
                feature.geometry.coordinates.forEach((lineString, lineIndex) => {
                    const pathNodes = []; // Store nodes for this path segment

                    for (let i = 0; i < lineString.length; i++) {
                        const coord = lineString[i];
                        const lat = coord[1];
                        const lng = coord[0];

                        // Check if a node already exists nearby
                        let nodeId = null;
                        let existingNode = null;

                        for (const [id, node] of nodeMap) {
                            if (this.calculateDistance(lat, lng, node.lat, node.lng) <
                                tolerance) {
                                nodeId = id;
                                existingNode = node;
                                break;
                            }
                        }

                        // Create new node if none exists nearby
                        if (!existingNode) {
                            nodeId = `${featureIndex}_${lineIndex}_${i}`;
                            const newNode = {
                                id: nodeId,
                                lat: lat,
                                lng: lng,
                                connections: []
                            };
                            this.nodes.push(newNode);
                            nodeMap.set(nodeId, newNode);
                        }

                        pathNodes.push(nodeId);
                    }

                    // Create edges for this path segment
                    for (let i = 0; i < pathNodes.length - 1; i++) {
                        const fromNodeId = pathNodes[i];
                        const toNodeId = pathNodes[i + 1];
                        const fromNode = nodeMap.get(fromNodeId);
                        const toNode = nodeMap.get(toNodeId);

                        // Check if this edge would cross any boundary
                        if (!this.doesLineCrossBoundary(fromNode.lat, fromNode.lng, toNode.lat,
                                toNode.lng)) {
                            const distance = this.calculateDistance(
                                fromNode.lat, fromNode.lng,
                                toNode.lat, toNode.lng
                            );

                            // Create bidirectional edges
                            this.edges.push({
                                from: fromNodeId,
                                to: toNodeId,
                                distance: distance
                            });
                            this.edges.push({
                                from: toNodeId,
                                to: fromNodeId,
                                distance: distance
                            });

                            // Track connections
                            if (!fromNode.connections.includes(toNodeId)) {
                                fromNode.connections.push(toNodeId);
                            }
                            if (!toNode.connections.includes(fromNodeId)) {
                                toNode.connections.push(fromNodeId);
                            }
                        } else {
                            console.log(
                                `🚫 Blocked edge ${fromNodeId} -> ${toNodeId} (crosses boundary)`
                            );
                        }
                    }
                });
            }
        });

        // Additional connections for nodes that are very close but from different paths
        this.connectNearbyPaths(15); // 15 meters for cross-path connections

        // Count edges that were created vs blocked
        const totalEdges = this.edges.length;
        const blockedEdgesInPaths = document.querySelectorAll('script')
            .length; // This will be updated with actual count

        console.log(`✅ Graph built: ${this.nodes.length} nodes, ${totalEdges} edges`);
        console.log(`🛡️ Boundary checking: Navigation will only use valid walkable paths`);

        // Debug: log some sample connections
        const sampleNode = this.nodes[0];
        if (sampleNode) {
            console.log(`📝 Sample node ${sampleNode.id} has ${sampleNode.connections.length} connections:`,
                sampleNode.connections.slice(0, 3) + (sampleNode.connections.length > 3 ? '...' : ''));
        }
    },

    /**
     * Find a node near the given coordinates
     */
    findNearbyNode: function(lat, lng, tolerance) {
        return this.nodes.find(node =>
            Math.abs(node.lat - lat) < tolerance &&
            Math.abs(node.lng - lng) < tolerance
        );
    },

    /**
     * Connect nodes that are close to each other but from different path segments
     * Only creates connections that don't cross cemetery boundaries
     */
    connectNearbyPaths: function(connectionTolerance) {
        let connectionsAdded = 0;
        let connectionsBlocked = 0;

        for (let i = 0; i < this.nodes.length; i++) {
            for (let j = i + 1; j < this.nodes.length; j++) {
                const node1 = this.nodes[i];
                const node2 = this.nodes[j];

                // Skip if nodes are already connected
                if (node1.connections.includes(node2.id)) continue;

                const distance = this.calculateDistance(node1.lat, node1.lng, node2.lat, node2.lng);

                if (distance < connectionTolerance) {
                    // Check if this connection would cross any boundary
                    if (!this.doesLineCrossBoundary(node1.lat, node1.lng, node2.lat, node2.lng)) {
                        // Create bidirectional connection
                        this.edges.push({
                            from: node1.id,
                            to: node2.id,
                            distance: distance
                        });
                        this.edges.push({
                            from: node2.id,
                            to: node1.id,
                            distance: distance
                        });

                        node1.connections.push(node2.id);
                        node2.connections.push(node1.id);
                        connectionsAdded++;
                    } else {
                        connectionsBlocked++;
                    }
                }
            }
        }

        console.log(`🔗 Added ${connectionsAdded} cross-path connections`);
        console.log(`🚫 Blocked ${connectionsBlocked} connections that would cross boundaries`);
    },

    /**
     * Check if a line segment intersects with any cemetery boundary
     */
    doesLineCrossBoundary: function(lat1, lng1, lat2, lng2) {
        if (typeof json_boundary_0 === 'undefined') {
            return false; // No boundary data available, allow connection
        }

        // Check against all boundary features
        for (const feature of json_boundary_0.features) {
            if (feature.geometry.type === 'MultiLineString') {
                for (const lineString of feature.geometry.coordinates) {
                    // Check each segment of the boundary
                    for (let i = 0; i < lineString.length - 1; i++) {
                        const boundaryLat1 = lineString[i][1];
                        const boundaryLng1 = lineString[i][0];
                        const boundaryLat2 = lineString[i + 1][1];
                        const boundaryLng2 = lineString[i + 1][0];

                        if (this.linesIntersect(
                                lat1, lng1, lat2, lng2,
                                boundaryLat1, boundaryLng1, boundaryLat2, boundaryLng2
                            )) {
                            // Debug logging for boundary intersections
                            console.log(
                                `🚫 Path blocked: [${lat1.toFixed(6)}, ${lng1.toFixed(6)}] to [${lat2.toFixed(6)}, ${lng2.toFixed(6)}] crosses boundary at [${boundaryLat1.toFixed(6)}, ${boundaryLng1.toFixed(6)}] to [${boundaryLat2.toFixed(6)}, ${boundaryLng2.toFixed(6)}]`
                            );
                            return true; // Lines intersect, connection blocked
                        }
                    }
                }
            }
        }
        return false; // No intersection found
    },

    /**
     * Check if two line segments intersect
     * Uses the orientation method to determine if lines intersect
     */
    linesIntersect: function(lat1, lng1, lat2, lng2, lat3, lng3, lat4, lng4) {
        // Calculate orientations
        const o1 = this.orientation(lat1, lng1, lat2, lng2, lat3, lng3);
        const o2 = this.orientation(lat1, lng1, lat2, lng2, lat4, lng4);
        const o3 = this.orientation(lat3, lng3, lat4, lng4, lat1, lng1);
        const o4 = this.orientation(lat3, lng3, lat4, lng4, lat2, lng2);

        // General case: lines intersect if orientations are different
        if (o1 !== o2 && o3 !== o4) {
            return true;
        }

        // Special cases: check if points are collinear and on the segment
        if (o1 === 0 && this.onSegment(lat1, lng1, lat3, lng3, lat2, lng2)) return true;
        if (o2 === 0 && this.onSegment(lat1, lng1, lat4, lng4, lat2, lng2)) return true;
        if (o3 === 0 && this.onSegment(lat3, lng3, lat1, lng1, lat4, lng4)) return true;
        if (o4 === 0 && this.onSegment(lat3, lng3, lat2, lng2, lat4, lng4)) return true;

        return false;
    },

    /**
     * Calculate orientation of ordered triplet of points
     * Returns 0 if collinear, 1 if clockwise, 2 if counterclockwise
     */
    orientation: function(lat1, lng1, lat2, lng2, lat3, lng3) {
        const val = (lng2 - lng1) * (lat3 - lat2) - (lat2 - lat1) * (lng3 - lng2);
        if (Math.abs(val) < 1e-10) return 0; // Collinear (with small tolerance)
        return (val > 0) ? 1 : 2; // Clockwise or counterclockwise
    },

    /**
     * Check if point (lat2, lng2) lies on line segment from (lat1, lng1) to (lat3, lng3)
     */
    onSegment: function(lat1, lng1, lat2, lng2, lat3, lng3) {
        return lat2 <= Math.max(lat1, lat3) && lat2 >= Math.min(lat1, lat3) &&
            lng2 <= Math.max(lng1, lng3) && lng2 >= Math.min(lng1, lng3);
    },

    /**
     * Calculate distance between two coordinates using Haversine formula (in meters)
     */
    calculateDistance: function(lat1, lng1, lat2, lng2) {
        const R = 6371000; // Earth's radius in meters
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Distance in meters
    },

    /**
     * Find the nearest path node to a given coordinate
     */
    findNearestNode: function(lat, lng) {
        let nearestNode = null;
        let minDistance = Infinity;

        this.nodes.forEach(node => {
            const distance = this.calculateDistance(lat, lng, node.lat, node.lng);
            if (distance < minDistance) {
                minDistance = distance;
                nearestNode = node;
            }
        });

        if (nearestNode) {
            console.log(
                `📍 Nearest node to [${lat.toFixed(6)}, ${lng.toFixed(6)}] is ${nearestNode.id} at ${minDistance.toFixed(1)}m`
            );
        } else {
            console.log(`❌ No nearest node found for [${lat.toFixed(6)}, ${lng.toFixed(6)}]`);
        }

        return nearestNode;
    },

    /**
     * Find path between two points using Dijkstra's algorithm
     */
    findPath: function(startLat, startLng, endLat, endLng) {
        console.log('🔍 Finding cemetery path from', [startLat, startLng], 'to', [endLat, endLng]);

        // Find nearest nodes to start and end points
        const startNode = this.findNearestNode(startLat, startLng);
        const endNode = this.findNearestNode(endLat, endLng);

        if (!startNode || !endNode) {
            console.log('❌ Could not find path nodes');
            return null;
        }

        console.log('📍 Using nodes:', startNode.id, '->', endNode.id);
        console.log('📊 Start node connections:', startNode.connections.length);
        console.log('📊 End node connections:', endNode.connections.length);

        // If start and end are the same node, return direct path
        if (startNode.id === endNode.id) {
            console.log('✅ Start and end are same node, returning direct path');
            return [
                [startLat, startLng],
                [endLat, endLng]
            ];
        }

        // Dijkstra's algorithm implementation
        const distances = {};
        const previous = {};
        const unvisited = new Set();

        // Initialize distances
        this.nodes.forEach(node => {
            distances[node.id] = Infinity;
            previous[node.id] = null;
            unvisited.add(node.id);
        });

        distances[startNode.id] = 0;

        while (unvisited.size > 0) {
            // Find unvisited node with minimum distance
            let currentNode = null;
            let minDistance = Infinity;

            unvisited.forEach(nodeId => {
                if (distances[nodeId] < minDistance) {
                    minDistance = distances[nodeId];
                    currentNode = nodeId;
                }
            });

            if (currentNode === null || minDistance === Infinity) {
                console.log('⚠️ No more reachable nodes, breaking from pathfinding');
                break;
            }

            unvisited.delete(currentNode);

            // If we reached the destination
            if (currentNode === endNode.id) {
                console.log('🎯 Reached destination node');
                break;
            }

            // Check all neighbors
            this.edges.forEach(edge => {
                if (edge.from === currentNode && unvisited.has(edge.to)) {
                    const newDistance = distances[currentNode] + edge.distance;
                    if (newDistance < distances[edge.to]) {
                        distances[edge.to] = newDistance;
                        previous[edge.to] = currentNode;
                    }
                }
            });
        }

        // Check if we found a path to the destination
        if (distances[endNode.id] === Infinity) {
            console.log('❌ No path found - destination unreachable');

            // Try to find any connected nodes near the destination
            let alternativeEndNode = null;
            let minDistToEnd = Infinity;

            this.nodes.forEach(node => {
                if (distances[node.id] !== Infinity) {
                    const distToEnd = this.calculateDistance(node.lat, node.lng, endLat, endLng);
                    if (distToEnd < minDistToEnd) {
                        minDistToEnd = distToEnd;
                        alternativeEndNode = node;
                    }
                }
            });

            if (alternativeEndNode && minDistToEnd < 50) { // Within 50 meters
                console.log(
                    `🔄 Using alternative end node ${alternativeEndNode.id} (${minDistToEnd.toFixed(1)}m away)`
                );
                // Reconstruct path to alternative end node
                const path = [];
                let currentNode = alternativeEndNode.id;

                while (currentNode !== null) {
                    const node = this.nodes.find(n => n.id === currentNode);
                    if (node) {
                        path.unshift([node.lat, node.lng]);
                    }
                    currentNode = previous[currentNode];
                }

                // Add final destination
                if (path.length > 0) {
                    path.push([endLat, endLng]);
                    console.log('✅ Cemetery path found with alternative end point:', path.length, 'waypoints');
                    return path;
                }
            }

            return null;
        }

        // Reconstruct path
        const path = [];
        let currentNode = endNode.id;

        while (currentNode !== null) {
            const node = this.nodes.find(n => n.id === currentNode);
            if (node) {
                path.unshift([node.lat, node.lng]);
            }
            currentNode = previous[currentNode];
        }

        if (path.length === 0) {
            console.log('❌ Failed to reconstruct path');
            return null;
        }

        // Add start and end coordinates if they're different from path endpoints
        if (path.length > 0) {
            const firstPoint = path[0];
            const lastPoint = path[path.length - 1];

            // Add actual start location if different
            if (this.calculateDistance(startLat, startLng, firstPoint[0], firstPoint[1]) > 2) {
                path.unshift([startLat, startLng]);
            }

            // Add actual end location if different
            if (this.calculateDistance(endLat, endLng, lastPoint[0], lastPoint[1]) > 2) {
                path.push([endLat, endLng]);
            }
        }

        console.log('✅ Cemetery path found with', path.length, 'waypoints');
        return path;
    }
};

// Initialize the cemetery pathfinder when the map is ready
map.whenReady(function() {
    // Wait for json_path_1 and json_boundary_0 to be loaded
    setTimeout(function() {
        if (typeof json_path_1 !== 'undefined') {
            if (typeof json_boundary_0 !== 'undefined') {
                const boundaryCount = json_boundary_0.features.length;
                let segmentCount = 0;
                json_boundary_0.features.forEach(feature => {
                    if (feature.geometry.type === 'MultiLineString') {
                        feature.geometry.coordinates.forEach(lineString => {
                            segmentCount += lineString.length - 1;
                        });
                    }
                });
                console.log(
                    `🛡️ Boundary data loaded: ${boundaryCount} features, ${segmentCount} boundary segments`
                );
                console.log(
                    '✅ Navigation will respect cemetery boundaries and avoid crossing walls/fences');
            } else {
                console.warn(
                    '⚠️ Boundary data (json_boundary_0) not found. Navigation may cross barriers.');
            }
            CemeteryPathfinder.initialize();
            console.log('🏛️ Cemetery pathfinding system initialized');
        } else {
            console.warn('⚠️ Cemetery path data (json_path_1) not found. Custom routing unavailable.');
        }
    }, 1000);
});
</script>