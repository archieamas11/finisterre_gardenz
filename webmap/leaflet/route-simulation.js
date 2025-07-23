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
        var coords = routeLine.getLatLngs();
        simulationState.simulationCoordinates = simulationState.simulationCoordinates.concat(coords);
    });
    simulationState.simulationIndex = 0;
    simulationState.isSimulating = true;
    console.log('🎬 Starting route simulation with', simulationState.simulationCoordinates.length, 'waypoints');
    simulationState.simulationInterval = setInterval(function() {
        if (simulationState.simulationIndex < simulationState.simulationCoordinates.length) {
            var currentPoint = simulationState.simulationCoordinates[simulationState.simulationIndex];
            if (navigationState.userMarker) {
                navigationState.userMarker.setLatLng(currentPoint);
                if (navigationState.totalRouteCoordinates.length > 0) {
                    checkForReroute(currentPoint.lat, currentPoint.lng);
                    if (typeof updateDirectionsPanel === 'function') updateDirectionsPanel();
                }
                console.log('🎯 Simulated position:', [currentPoint.lat, currentPoint.lng], '(Step', simulationState.simulationIndex + 1, 'of', simulationState.simulationCoordinates.length + ')');
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
