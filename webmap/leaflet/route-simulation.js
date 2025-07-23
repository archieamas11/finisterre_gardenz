/**
 * Route Simulation Module (separated from get-route.js)
 * Handles user movement simulation along the route for testing
 */

// Assumes navigationState and map are available globally
// Simulation state
var simulationState = {
    isSimulating: false,
    simulationInterval: null,
    simulationIndex: 0,
    simulationCoordinates: [],
    simulationSpeed: 1000 // milliseconds between moves (1 second)
};

function startRouteSimulation() {
    if (!navigationState.isActive || navigationState.routeLines.length === 0) {
        console.warn('⚠️ Cannot start simulation: no active route');
        return;
    }
    simulationState.simulationCoordinates = [];
    navigationState.routeLines.forEach(function(routeLine) {
        // Defensive: getLatLngs() may return nested arrays for multi-segment polylines
        var coords = routeLine.getLatLngs();
        if (Array.isArray(coords[0])) {
            // Flatten if needed
            coords = coords.flat();
        }
        simulationState.simulationCoordinates = simulationState.simulationCoordinates.concat(coords);
    });
    simulationState.simulationIndex = 0;
    simulationState.isSimulating = true;
    console.log('🎬 Starting route simulation with', simulationState.simulationCoordinates.length, 'waypoints');
    if (simulationState.simulationCoordinates.length === 0) {
        console.warn('⚠️ No coordinates to simulate.');
        simulationState.isSimulating = false;
        return;
    }
    simulationState.simulationInterval = setInterval(function() {
        if (simulationState.simulationIndex < simulationState.simulationCoordinates.length) {
            var currentPoint = simulationState.simulationCoordinates[simulationState.simulationIndex];
            if (navigationState.userMarker && currentPoint) {
                // Defensive: support both [lat, lng] and LatLng objects
                var lat = currentPoint.lat !== undefined ? currentPoint.lat : currentPoint[0];
                var lng = currentPoint.lng !== undefined ? currentPoint.lng : currentPoint[1];
                navigationState.userMarker.setLatLng([lat, lng]);
                if (navigationState.totalRouteCoordinates && navigationState.totalRouteCoordinates.length > 0) {
                    if (typeof checkForReroute === 'function') checkForReroute(lat, lng);
                    if (typeof updateDirectionsPanel === 'function') updateDirectionsPanel();
                }
                console.log('🎯 Simulated position:', [lat, lng], '(Step', simulationState.simulationIndex + 1, 'of', simulationState.simulationCoordinates.length + ')');
            }
            simulationState.simulationIndex++;
        } else {
            console.log('🏁 Simulation completed - reached destination!');
            stopRouteSimulation();
        }
    }, simulationState.simulationSpeed);
    if (typeof updateNavigationPanelForSimulation === 'function') updateNavigationPanelForSimulation(true);
}

function stopRouteSimulation() {
    if (simulationState.simulationInterval) {
        clearInterval(simulationState.simulationInterval);
        simulationState.simulationInterval = null;
    }
    simulationState.isSimulating = false;
    simulationState.simulationIndex = 0;
    simulationState.simulationCoordinates = [];
    console.log('⏹️ Route simulation stopped');
    if (typeof updateNavigationPanelForSimulation === 'function') updateNavigationPanelForSimulation(false);
}

window.startRouteSimulation = startRouteSimulation;
window.stopRouteSimulation = stopRouteSimulation;
