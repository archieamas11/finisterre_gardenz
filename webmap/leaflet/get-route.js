/**
 * Custom Routing System
 * Two-step routing: City→Gate (public OSRM) + Gate→Grave (local OSRM)
 * With live GPS tracking every 3 seconds
 */

/** Cemetery main entrance coordinate
* this will snap the route to the nearest
* point of this coordinate and switch from 
* public OSRM to local OSRM **/
var CEMETERY_GATE = L.latLng(10.248107820799307, 123.797607547609545);

// Navigation state
var navigationState = {
    isActive: false,
    watchId: null,
    currentRoute: null,
    userMarker: null,
    routeLines: [],
    destinationMarker: null
};

/**
 * Main navigation function - called in get-direction button
 */
function navigateToGrave(graveLat, graveLng) {
    console.log('🚀 Starting two-step navigation to grave:', [graveLat, graveLng]);
    
    if (!navigator.geolocation) {
        alert('GPS not supported by your browser');
        return;
    }

    // Clear any existing navigation
    stopNavigation();

    // Get user's current location
    navigator.geolocation.getCurrentPosition(
        function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;
            
            console.log('📍 User location:', [userLat, userLng]);
            
            // Start two-step routing
            createTwoStepRoute(userLat, userLng, graveLat, graveLng);
        },
        function(error) {
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

/**
 * Create the two-step route: City→Gate + Gate→Grave
 */
function createTwoStepRoute(userLat, userLng, graveLat, graveLng) {
    console.log('🛣️ Creating two-step route...');
    
    var gateLat = CEMETERY_GATE.lat;
    var gateLng = CEMETERY_GATE.lng;
    
    // Step 1: Route from user to cemetery gate (public OSRM)
    getRoute(
        userLat, userLng, gateLat, gateLng,
        'https://router.project-osrm.org/route/v1/driving', // Public OSRM for city travel
        '#FF6B6B', // Red color for city route - change color here
        function(step1Route) {
            console.log('✅ Step 1 (City→Gate) route found');
            
            // Step 2: Route from gate to grave (local OSRM foot profile)
            getRoute(
                gateLat, gateLng, graveLat, graveLng,
                'http://localhost:5000/route/v1/foot', // Local OSRM for cemetery walking
                '#4ECDC4', // Teal color for cemetery route - change color here
                function(step2Route) {
                    console.log('✅ Step 2 (Gate→Grave) route found');
                    
                    // Add final segment from footpath to exact grave location
                    var finalRoute = extendRouteToGrave(step2Route, graveLat, graveLng);
                    
                    // Display both routes and start tracking
                    displayRoutes([step1Route, finalRoute], userLat, userLng, graveLat, graveLng);
                    startLiveTracking();
                }
            );
        }
    );
}

/**
 * Get route from OSRM service
 */
function getRoute(startLat, startLng, endLat, endLng, serviceUrl, color, callback) {
    var url = serviceUrl + '/' + startLng + ',' + startLat + ';' + endLng + ',' + endLat + 
              '?overview=full&geometries=geojson';
    
    fetch(url)
        .then(function(response) {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(function(data) {
            if (data.routes && data.routes.length > 0) {
                var route = data.routes[0];
                var coordinates = route.geometry.coordinates.map(function(coord) {
                    return [coord[1], coord[0]]; // Convert [lng, lat] to [lat, lng]
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
        .catch(function(error) {
            console.error('Routing error:', error);
            alert('Could not find route. Please try again.');
        });
}

/**
 * Extend route to snap directly to the grave location
 * Adds a final segment from the footpath endpoint to the exact grave coordinates
 */
function extendRouteToGrave(footpathRoute, graveLat, graveLng) {
    var coordinates = footpathRoute.coordinates.slice(); // Copy the footpath coordinates
    var lastPoint = coordinates[coordinates.length - 1];
    var lastLat = lastPoint[0];
    var lastLng = lastPoint[1];
    
    // Calculate distance between footpath end and grave
    var distance = calculateDistanceBetweenPoints(lastLat, lastLng, graveLat, graveLng);
    
    // Only add direct connection if grave is more than 2 meters from footpath end
    if (distance > 2) {
        console.log('📍 Adding direct connection to grave (' + distance.toFixed(1) + 'm from footpath)');
        coordinates.push([graveLat, graveLng]); // Add direct line to grave
        
        // Update the route distance and duration
        return {
            coordinates: coordinates,
            distance: footpathRoute.distance + distance,
            duration: footpathRoute.duration + (distance / 1.4), // Walking speed 1.4 m/s
            color: footpathRoute.color
        };
    }
    
    return footpathRoute; // Return original if grave is already close to footpath
}

/**
 * Calculate distance between two points in meters using Haversine formula
 */
function calculateDistanceBetweenPoints(lat1, lng1, lat2, lng2) {
    var R = 6371000; // Earth's radius in meters
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLng = (lng2 - lng1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

/**
 * Create custom user location icon with FontAwesome
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
 * Create custom destination icon with FontAwesome
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
 * Display the routes on the map
 */
function displayRoutes(routes, userLat, userLng, graveLat, graveLng) {
    // Clear existing routes
    navigationState.routeLines.forEach(function(line) {
        map.removeLayer(line);
    });
    navigationState.routeLines = [];
    
    // Add route lines
    routes.forEach(function(route) {
        var routeLine = L.polyline(route.coordinates, {
            color: route.color,
            weight: 6,
            opacity: 0.8
        }).addTo(map);
        
        navigationState.routeLines.push(routeLine);
    });
    
    // Add user marker (styled with FontAwesome icon - change style here)
    navigationState.userMarker = L.marker([userLat, userLng], {
        icon: createUserIcon()
    }).addTo(map);
    
    // Add destination marker (styled map pin - change style here)
    navigationState.destinationMarker = L.marker([graveLat, graveLng], {
        icon: createDestinationIcon()
    }).addTo(map);
    
    // Fit map to show full route
    var group = L.featureGroup([
        navigationState.userMarker,
        navigationState.destinationMarker
    ].concat(navigationState.routeLines));
    map.fitBounds(group.getBounds().pad(0.1));
    
    // Calculate total stats
    var totalDistance = routes.reduce(function(sum, route) { return sum + route.distance; }, 0);
    var totalDuration = routes.reduce(function(sum, route) { return sum + route.duration; }, 0);
    
    showNavigationPanel(totalDistance, totalDuration);
    navigationState.isActive = true;
}

/**
 * Start live GPS tracking (updates every 3 seconds)
 */
function startLiveTracking() {
    if (!navigator.geolocation) return;
    
    console.log('🔄 Starting live GPS tracking (every 3 seconds)');
    
    navigationState.watchId = navigator.geolocation.watchPosition(
        function(position) {
            if (navigationState.isActive && navigationState.userMarker) {
                var newLat = position.coords.latitude;
                var newLng = position.coords.longitude;
                
                // Update user marker position
                navigationState.userMarker.setLatLng([newLat, newLng]);
                
                console.log('📡 Updated user position:', [newLat, newLng]);
            }
        },
        function(error) {
            console.warn('Live tracking error:', error);
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 3000 // 3 second intervals
        }
    );
}

/**
 * Show navigation info panel with Font Awesome icons and directions
 */
function showNavigationPanel(totalDistance, totalDuration) {
    var distanceKm = (totalDistance / 1000).toFixed(1);
    var durationMin = Math.round(totalDuration / 60);
    
    // Remove existing panels
    var existingPanel = document.getElementById('nav-panel');
    var existingDirections = document.getElementById('directions-panel');
    if (existingPanel) existingPanel.remove();
    if (existingDirections) existingDirections.remove();
    
    // Create main navigation panel
    var panel = document.createElement('div');
    panel.id = 'nav-panel';
    panel.style.cssText = `
        position: fixed;
        top: 20px;
        left: 20px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        font-family: Arial, sans-serif;
        min-width: 280px;
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
    
    // Create directions panel below main panel
    var directionsPanel = document.createElement('div');
    directionsPanel.id = 'directions-panel';
    directionsPanel.style.cssText = `
        position: fixed;
        top: 160px;
        left: 20px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        font-family: Arial, sans-serif;
        min-width: 280px;
        border: 1px solid #e0e0e0;
    `;
    
    // Get current direction
    var currentDirection = getCurrentDirection();
    
    directionsPanel.innerHTML = `
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div style="background: #007AFF; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                <i class="${currentDirection.icon}" style="color: white; font-size: 18px;"></i>
            </div>
            <div>
                <div style="font-weight: bold; color: #333; font-size: 16px;">${currentDirection.instruction}</div>
                <div style="color: #666; font-size: 14px; margin-top: 2px;">
                    <i class="fas fa-location-arrow" style="margin-right: 4px; font-size: 12px;"></i>
                    ${currentDirection.distance}
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(panel);
    document.body.appendChild(directionsPanel);
}

/**
 * Get current navigation direction based on navigation state
 */
function getCurrentDirection() {
    // Simple direction logic - you can enhance this based on your route data
    if (!navigationState.isActive) {
        return {
            icon: 'fas fa-map-marker-alt',
            instruction: 'Start navigation',
            distance: ''
        };
    }
    
    // Determine current step based on route progress
    // For now, showing generic directions - can be enhanced with actual route analysis
    var directions = [
        {
            icon: 'fas fa-arrow-up',
            instruction: 'Head toward cemetery gate',
            distance: formatDistance(500) // Example distance
        },
        {
            icon: 'fas fa-arrow-right',
            instruction: 'Enter cemetery grounds',
            distance: formatDistance(200)
        },
        {
            icon: 'fas fa-walking',
            instruction: 'Follow cemetery path',
            distance: formatDistance(150)
        },
        {
            icon: 'fas fa-flag-checkered',
            instruction: 'Arrive at destination',
            distance: formatDistance(50)
        }
    ];
    
    // Return first direction for now - can be enhanced with GPS-based progress tracking
    return directions[0];
}

/**
 * Format distance for display (meters or kilometers)
 */
function formatDistance(meters) {
    if (meters < 1000) {
        return Math.round(meters) + 'm';
    } else {
        return (meters / 1000).toFixed(1) + 'km';
    }
}

/**
 * Stop navigation and clean up everything
 */
function stopNavigation() {
    console.log('🛑 Stopping navigation...');
    
    // Stop GPS tracking
    if (navigationState.watchId) {
        navigator.geolocation.clearWatch(navigationState.watchId);
        navigationState.watchId = null;
    }
    
    // Remove route lines
    navigationState.routeLines.forEach(function(line) {
        map.removeLayer(line);
    });
    navigationState.routeLines = [];
    
    // Remove markers
    if (navigationState.userMarker) {
        map.removeLayer(navigationState.userMarker);
        navigationState.userMarker = null;
    }
    
    if (navigationState.destinationMarker) {
        map.removeLayer(navigationState.destinationMarker);
        navigationState.destinationMarker = null;
    }
    
    // Remove info panels
    var panel = document.getElementById('nav-panel');
    var directionsPanel = document.getElementById('directions-panel');
    if (panel) panel.remove();
    if (directionsPanel) directionsPanel.remove();
    
    // Reset state
    navigationState.isActive = false;
    navigationState.currentRoute = null;
    
    console.log('✅ Navigation stopped and cleaned up');
}

// Make functions globally available
window.navigateToGrave = navigateToGrave;
window.stopNavigation = stopNavigation;

// Add custom marker styles to the page
(function addMarkerStyles() {
    var style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);
})();

console.log('🗺️ Clean Cemetery Navigation System loaded');
console.log('📍 Cemetery gate set to:', CEMETERY_GATE.lat, CEMETERY_GATE.lng);
console.log('🎯 Usage: navigateToGrave(lat, lng) or stopNavigation()');
