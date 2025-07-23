// Cemetery main entrance coordinate
var CEMETERY_GATE = L.latLng(10.248107820799307, 123.797607547609545);

// Navigation state with new properties
var navigationState = {
    isActive: false,
    watchId: null,
    currentRoute: null,
    userMarker: null,
    routeLines: [],
    destinationMarker: null,
    destination: null,          // Added to store grave coordinates
    previousStartPoint: null,  // Added to track last route start
    isRecalculating: false     // Added to prevent overlapping recalculations
};

function navigateToGrave(graveLat, graveLng) {
    console.log('🚀 Starting two-step navigation to grave:', [graveLat, graveLng]);

    if (!navigator.geolocation) {
        alert('GPS not supported by your browser');
        return;
    }

    stopNavigation();

    navigator.geolocation.getCurrentPosition(
        function (position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;

            console.log('📍 User location:', [userLat, userLng]);

            // Set destination and initial start point
            navigationState.destination = L.latLng(graveLat, graveLng);
            navigationState.previousStartPoint = L.latLng(userLat, userLng);

            createTwoStepRoute(userLat, userLng, graveLat, graveLng);
        },
        function (error) {
            alert('Could not get your location. Please enable GPS and try again.');
            console.error('Geolocation error:', error);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 30000
        }
    );
}

function createTwoStepRoute(userLat, userLng, graveLat, graveLng) {
    var gateLat = CEMETERY_GATE.lat;
    var gateLng = CEMETERY_GATE.lng;

    getRoute(
        userLat, userLng, gateLat, gateLng,
        'https://router.project-osrm.org/route/v1/driving',
        '#FF6B6B',
        function (step1Route) {
            console.log('✅ Step 1 (City→Gate) route found');

            getRoute(
                gateLat, gateLng, graveLat, graveLng,
                'https://finisterreosm-production.up.railway.app/route/v1/foot',
                '#4ECDC4',
                function (step2Route) {
                    console.log('✅ Step 2 (Gate→Grave) route found');

                    var finalRoute = extendRouteToGrave(step2Route, graveLat, graveLng);
                    displayRoutes([step1Route, finalRoute], userLat, userLng, graveLat, graveLng);
                    startLiveTracking();
                    // Start route simulation if available
                    if (typeof startRouteSimulation === 'function') {
                        setTimeout(function () {
                            console.log('🎬 Starting route simulation...');
                            startRouteSimulation();
                        }, 500);
                    }
                }
            );
        }
    );
}

function getRoute(startLat, startLng, endLat, endLng, serviceUrl, color, callback) {
    var url = serviceUrl + '/' + startLng + ',' + startLat + ';' + endLng + ',' + endLat +
        '?overview=full&geometries=geojson';

    fetch(url)
        .then(function (response) {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(function (data) {
            if (data.routes && data.routes.length > 0) {
                var route = data.routes[0];
                var coordinates = route.geometry.coordinates.map(function (coord) {
                    return [coord[1], coord[0]];
                });

                callback({
                    coordinates: coordinates,
                    distance: route.distance,
                    duration: route.duration,
                    color: color
                });
            } else {
                throw new Error('No route found');
            }
        })
        .catch(function (error) {
            console.error('Routing error:', error);
            alert('Could not find route. Please try again.');
        });
}

function extendRouteToGrave(footpathRoute, graveLat, graveLng) {
    var coordinates = footpathRoute.coordinates.slice();
    var lastPoint = coordinates[coordinates.length - 1];
    var lastLat = lastPoint[0];
    var lastLng = lastPoint[1];

    var distance = calculateDistanceBetweenPoints(lastLat, lastLng, graveLat, graveLng);

    if (distance > 2) {
        coordinates.push([graveLat, graveLng]);
        return {
            coordinates: coordinates,
            distance: footpathRoute.distance + distance,
            duration: footpathRoute.duration + (distance / 1.4),
            color: footpathRoute.color
        };
    }

    return footpathRoute;
}

function calculateDistanceBetweenPoints(lat1, lng1, lat2, lng2) {
    var R = 6371000;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLng = (lng2 - lng1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

function createUserIcon() {
    return L.divIcon({
        html: '<div class="user-marker"><i class="fas fa-user"></i></div>',
        className: 'custom-user-marker',
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });
}

function createDestinationIcon() {
    return L.divIcon({
        html: '<div class="destination-marker"><i class="fas fa-map-marker-alt"></i></div>',
        className: 'custom-destination-marker',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
    });
}

function displayRoutes(routes, userLat, userLng, graveLat, graveLng) {
    if (navigationState.userMarker) {
        map.removeLayer(navigationState.userMarker);
    }
    if (navigationState.destinationMarker) {
        map.removeLayer(navigationState.destinationMarker);
    }

    navigationState.routeLines.forEach(function (line) {
        map.removeLayer(line);
    });
    navigationState.routeLines = [];

    navigationState.userMarker = L.marker([userLat, userLng], {
        icon: createUserIcon()
    }).addTo(map);

    navigationState.destinationMarker = L.marker([graveLat, graveLng], {
        icon: createDestinationIcon()
    }).addTo(map);

    if (routes.length > 0 && routes[0].coordinates.length > 0) {
        routes[0].coordinates[0] = [userLat, userLng];
    }

    routes.forEach(function (route) {
        var routeLine = L.polyline(route.coordinates, {
            color: route.color,
            weight: 6,
            opacity: 0.8
        }).addTo(map);
        navigationState.routeLines.push(routeLine);
    });

    var group = L.featureGroup([
        navigationState.userMarker,
        navigationState.destinationMarker
    ].concat(navigationState.routeLines));
    map.fitBounds(group.getBounds().pad(0.1));

    var totalDistance = routes.reduce(function (sum, route) { return sum + route.distance; }, 0);
    var totalDuration = routes.reduce(function (sum, route) { return sum + route.duration; }, 0);

    showNavigationPanel(totalDistance, totalDuration);
    navigationState.isActive = true;

    // Update previous start point and reset recalculating flag
    navigationState.previousStartPoint = L.latLng(userLat, userLng);
    navigationState.isRecalculating = false;
}

function startLiveTracking() {
    if (!navigator.geolocation) return;

    if (navigationState.watchId) {
        console.log('Live tracking already active');
        return;
    }

    console.log('🔄 Starting live GPS tracking (every 3 seconds)');

    navigationState.watchId = navigator.geolocation.watchPosition(
        function (position) {
            if (navigationState.isActive && navigationState.userMarker) {
                var newLat = position.coords.latitude;
                var newLng = position.coords.longitude;

                // Update user marker position
                navigationState.userMarker.setLatLng([newLat, newLng]);

                console.log('📡 Updated user position:', [newLat, newLng]);

                // Check distance from previous start point and recalculate if needed
                if (navigationState.previousStartPoint && navigationState.destination) {
                    var distance = calculateDistanceBetweenPoints(
                        newLat, newLng,
                        navigationState.previousStartPoint.lat,
                        navigationState.previousStartPoint.lng
                    );

                    if (distance > 20 && !navigationState.isRecalculating) {
                        console.log('🚧 User drifted > 20m, recalculating route...');
                        navigationState.isRecalculating = true;
                        createTwoStepRoute(
                            newLat, newLng,
                            navigationState.destination.lat,
                            navigationState.destination.lng
                        );
                    }
                }
            }
        },
        function (error) {
            console.warn('Live tracking error:', error);
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 3000
        }
    );
}

function showNavigationPanel(totalDistance, totalDuration) {
    var distanceKm = (totalDistance / 1000).toFixed(1);
    var durationMin = Math.round(totalDuration / 60);

    var existingPanel = document.getElementById('nav-panel');
    var existingDirections = document.getElementById('directions-panel');
    if (existingPanel) existingPanel.remove();
    if (existingDirections) existingDirections.remove();

    var panel = document.createElement('div');
    panel.id = 'nav-panel';
    panel.style.cssText = `
        position: fixed; bottom: 20px; left: 20px; background: white;
        padding: 15px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000; font-family: Arial, sans-serif; min-width: 280px;
        border: 1px solid #e0e0e0;
    `;

    panel.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-route" style="color: #007AFF; margin-right: 8px; font-size: 16px;"></i>
                <strong style="color: #333;">Navigation Active</strong>
            </div>
            <button onclick="stopNavigation()" style="background: #FF3B30; color: white; border: none; border-radius: 50%; padding: 8px; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-times" style="font-size: 12px;"></i>
            </button>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-road" style="color: #666; margin-right: 6px; font-size: 14px;"></i>
                <span style="font-size: 14px; color: #666;">${distanceKm} km</span>
            </div>
            <div style="display: flex; align-items: center;">
                <i class="fas fa-clock" style="color: #666; margin-right: 6px; font-size: 14px;"></i>
                <span style="font-size: 14px; color: #666;">${durationMin} min</span>
            </div>
        </div>
        <div style="display: flex; align-items: center; font-size: 12px; color: #999;">
            <i class="fas fa-location-dot" style="margin-right: 6px; color: #4CAF50;"></i>
            <span>Live tracking enabled</span>
        </div>
    `;
    document.body.appendChild(panel);
}

function formatDistance(meters) {
    if (meters < 1000) {
        return Math.round(meters) + 'm';
    } else {
        return (meters / 1000).toFixed(1) + 'km';
    }
}

function stopNavigation() {
    if (navigationState.watchId) {
        navigator.geolocation.clearWatch(navigationState.watchId);
        navigationState.watchId = null;
    }

    navigationState.routeLines.forEach(function (line) {
        map.removeLayer(line);
    });
    navigationState.routeLines = [];

    if (navigationState.userMarker) {
        map.removeLayer(navigationState.userMarker);
        navigationState.userMarker = null;
    }

    if (navigationState.destinationMarker) {
        map.removeLayer(navigationState.destinationMarker);
        navigationState.destinationMarker = null;
    }

    var panel = document.getElementById('nav-panel');
    var directionsPanel = document.getElementById('directions-panel');
    if (panel) panel.remove();
    if (directionsPanel) directionsPanel.remove();

    navigationState.isActive = false;
    navigationState.currentRoute = null;
    navigationState.destination = null;
    navigationState.previousStartPoint = null;
    navigationState.isRecalculating = false;

    console.log('✅ Navigation stopped and cleaned up');
}

window.navigateToGrave = navigateToGrave;
window.stopNavigation = stopNavigation;

(function addMarkerStyles() {
    var style = document.createElement('style');
    style.textContent = `
        .custom-user-marker .user-marker {
            background: #4CAF50; border: 3px solid #fff; border-radius: 50%;
            width: 30px; height: 30px; display: flex; align-items: center;
            justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            animation: pulse 2s infinite;
        }
        .custom-user-marker .user-marker i {
            color: white; font-size: 14px;
        }
        .custom-destination-marker .destination-marker {
            background: #f44336; border: 2px solid #fff; border-radius: 50% 50% 50% 0;
            width: 30px; height: 30px; display: flex; align-items: center;
            justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            transform: rotate(-45deg);
        }
        .custom-destination-marker .destination-marker i {
            color: white; font-size: 16px; transform: rotate(45deg);
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(76, 175, 80, 0); }
            100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
        }
    `;
    document.head.appendChild(style);
})();