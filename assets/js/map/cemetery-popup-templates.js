/**
 * Cemetery Map Popup Templates
 * Contains all template functions for generating popup content in the cemetery map
 * 
 * @author CemeterEase Development Team
 * @since 2025
 */

/**
 * Generates action buttons for popup based on user permissions and grave status
 * @param {Object} feature - GeoJSON feature object containing properties
 * @returns {string} HTML string for action buttons
 */
function getButtons(feature) {
    // Check if user type is available globally (set by PHP)
    const userType = window.userType || 'user';
    
    if (userType === 'user') {
        return '<div class="buttons"></div>';
    }

    console.log('User Type:', userType);
    
    const status = feature.properties['Status'];
    const recordId = feature.properties['id'];
    
    if (status === 'vacant' || status === 'reserved') {
        return '<div class="buttons"></div>';
    }
    
    return `
        <div class="buttons">
            <span class="edit-record-btn">
                <a href="index.php?id=${recordId || ''}&page=edit_record">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Plot Details">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </a>
            </span>
        </div>`;
}

/**
 * Template for empty graves
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML template for empty grave popup
 */
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
        </div> 
        <br>
        <hr>
        ${getPhotosSection(feature)}`;
}

/**
 * Template for reserved graves
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML template for reserved grave popup
 */
function getReservedTemplate(feature) {
    const contactPerson = feature.properties['Contact Person'];
    const formattedContact = contactPerson 
        ? window.autolinker.link(contactPerson.toLocaleString().replace(/\b\w/g, c => c.toUpperCase())) 
        : 'N/A';
    
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
                <span id="name" class="info-value">${formattedContact}</span>
            </div>
        </div>   
        <br>
        <hr>
        ${getPhotosSection(feature)}`;
}

/**
 * Template for graves with multiple interments
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML template for multiple interments popup
 */
function getMultipleGraveTemplate(feature) {
    const multipleNames = feature.properties['Multiple Names'];
    const formattedNames = multipleNames 
        ? window.autolinker.link(multipleNames.toLocaleString().replace(/\b\w/g, c => c.toUpperCase())) 
        : 'N/A';
    
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
        <div class="timeline-grid">
            <div class="icons">
                <i class="fas fa-user"></i>
                <div class="values">
                    <span class="info-label">Interred Persons</span>
                    <!-- Display multiple records -->
                    <div id="recordContainer">${formattedNames}</div>
                </div>
            </div>
        </div>   
        ${getPhotosSection(feature)}`;
}

/**
 * Template for graves with single interment
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML template for single interment popup
 */
function getSingleGraveTemplate(feature) {
    const name = feature.properties['Name'];
    const formattedName = name 
        ? window.autolinker.link(name.toLocaleString().replace(/\b\w/g, c => c.toUpperCase())) 
        : 'N/A';
    
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
                    <span id="name" class="info-value">${formattedName}</span>
                </div>
            </div>
            ${getLocationSection(feature)}
        </div>
        ${getTimelineSection(feature)}
        ${getPhotosSection(feature)}`;
}

/**
 * Generates location section for popup
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML string for location section
 */
function getLocationSection(feature) {
    const block = feature.properties['Block'] || 'N/A';
    const graveNo = feature.properties['Grave No.'] || 'N/A';
    const coordinates = feature.geometry.coordinates;
    
    return `
        <div class="icons">
            <i class="fas fa-map-pin"></i>
            <div class="values">
                <span class="info-label">Location</span> <br>
                <span id="block" class="info-value">Block ${block} • Grave ${graveNo}</span>
            </div>
            <div class="navigation-section">
                <a onclick="navigateToGrave(${coordinates[1]}, ${coordinates[0]})" class="direction-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#ffffff" d="M502.6 233.3L278.7 9.4c-12.5-12.5-32.8-12.5-45.4 0L9.4 233.3c-12.5 12.5-12.5 32.8 0 45.4l223.9 223.9c12.5 12.5 32.8 12.5 45.4 0l223.9-223.9c12.5-12.5 12.5-32.8 0-45.4zm-101 12.6l-84.2 77.7c-5.1 4.7-13.4 1.1-13.4-5.9V264h-96v64c0 4.4-3.6 8-8 8h-32c-4.4 0-8-3.6-8-8v-80c0-17.7 14.3-32 32-32h112v-53.7c0-7 8.3-10.6 13.4-5.9l84.2 77.7c3.4 3.2 3.4 8.6 0 11.8z"/>
                    </svg>
                </a>
            </div>
        </div>`;
}

/**
 * Generates timeline section for popup
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML string for timeline section
 */
function getTimelineSection(feature) {
    const birthDate = feature.properties['Birth'] || 'N/A';
    const deathDate = feature.properties['Death'] || 'N/A';
    const yearsBuried = feature.properties['Years Buried'] || 'N/A';
    
    return `
        <div class="timeline-grid">
            <div class="icons">
                <i class="fas fa-calendar"></i>
                <span class="timeline-title">Timeline</span>
            </div>
            <div class="container-map">
                <div class="date-left">
                    <span class="info-label">Birth</span> <br>
                    <span id="birthDate" class="info-value">${birthDate}</span>
                </div>
                <div class="date-right">
                    <span class="info-label">Death</span> <br>
                    <span id="deathDate" class="info-value">${deathDate}</span>
                </div>
            </div>
            <div class="seperator"></div>
            <div class="since">
                <span class="info-label">Time since Death</span> <br>
                <span id="timeSinceDeath" class="info-value">${yearsBuried}</span>
            </div>
        </div>`;
}

/**
 * Generates photos section for popup
 * @param {Object} feature - GeoJSON feature object
 * @returns {string} HTML string for photos section
 */
function getPhotosSection(feature) {
    const photoCount = feature.properties['PhotoCount'] || 0;
    const photos = feature.properties['Photos'];
    
    return `
        <div class="images-container" style="width: 100%;">
            ${photoCount > 0 
                ? `<div class="images-grid">${photos}</div>`
                : '<div style="display: flex; justify-content: center;"><br>No photos available</div>'}
        </div>`;
}

// Export functions for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        getButtons,
        empty,
        getReservedTemplate,
        getMultipleGraveTemplate,
        getSingleGraveTemplate,
        getLocationSection,
        getTimelineSection,
        getPhotosSection
    };
}
