<?php $result = mysqli_query($mysqli, "SELECT tbl_deceased.*, grave_points.*, tbl_files.record_id AS file_record_id FROM tbl_deceased
            RIGHT JOIN grave_points ON tbl_deceased.grave_id=grave_points.grave_id
            LEFT JOIN tbl_files ON tbl_files.record_id=tbl_deceased.record_id"); ?>
<script>
    var json_pointsfinall_3 = {
        "type": "FeatureCollection",
        "name": "pointsfinall_3",
        "crs": {
            "type": "name",
            "properties": {
                "name": "urn:ogc:def:crs:OGC:1.3:CRS84"
            }
        },
        "features": [
            <?php
            while ($row = mysqli_fetch_array($result)) {
            ?> {

                    "type": "Feature",
                    "properties": {
                        "Grave No.": "<?php echo $row['grave_id'] ?>",
                        "Visibility": "<?php echo $row['dead_visibility'] ?>",
                        "id": "<?php echo $row['record_id'] ?>",
                        "Name": "<?php
                                    $graveno = $row['grave_id'];
                                    $sql = "SELECT * FROM tbl_deceased WHERE grave_id = $graveno";
                                    if ($records = mysqli_query($mysqli, $sql)) {
                                        $counter4deceased = 1;
                                        while ($record = mysqli_fetch_assoc($records)) {
                                            echo $record['dead_fullname'];
                                            $counter4deceased++;
                                        }
                                    }
                                    ?>",
                        "Contact Person": "<?php
                                            $graveno = $row['grave_id'];
                                            $sql = "SELECT c.first_name, c.last_name, l.grave_id FROM tbl_lot l JOIN tbl_customers c ON c.customer_id = l.customer_id WHERE l.grave_id = $graveno";
                                            if ($records = mysqli_query($mysqli, $sql)) {
                                                $counter4deceased = 1;
                                                while ($record = mysqli_fetch_assoc($records)) {
                                                    echo $record['first_name'] . ' ' . $record['last_name'];
                                                    $counter4deceased++;
                                                }
                                            }
                                            ?>",
                        "Multiple Names": "<?php
                                            $graveno = $row['grave_id'];
                                            $sql = "SELECT dead_fullname, dead_birth_date, dead_date_death FROM tbl_deceased WHERE grave_id = $graveno";
                                            $output = '';
                                            if ($records = mysqli_query($mysqli, $sql)) {
                                                while ($record = mysqli_fetch_assoc($records)) {
                                                    $output .= '<div class=\"deceased-record\">';
                                                    $output .= '<strong>' . addslashes($record['dead_fullname']) . '</strong><br>';
                                                    $output .= '<small>Birth: ' . addslashes($record['dead_birth_date'] ?: 'N/A') . '</small><br>';
                                                    $output .= '<small>Death: ' . addslashes($record['dead_date_death'] ?: 'N/A') . '</small>';
                                                    $output .= '</div>';
                                                }
                                            }
                                            echo $output;
                                            ?>",
                        "DeceasedCount": "<?php echo $counter4deceased - 1; ?>",
                        "Birth": "<?php
                                    if ($duplicate = mysqli_query($mysqli, $sql)) {
                                        while ($dup = mysqli_fetch_assoc($duplicate)) {
                                            if (!empty($dup['dead_birth_date'])) {
                                                $date = new DateTime($dup['dead_birth_date']);
                                                echo $date->format('m/d/Y');
                                            } else {
                                                echo 'N/A';
                                            }
                                        }
                                    }
                                    ?>",
                        "Death": "<?php
                                    if ($duplicate = mysqli_query($mysqli, $sql)) {
                                        while ($dup = mysqli_fetch_assoc($duplicate)) {
                                            if (!empty($dup['dead_date_death'])) {
                                                $date = new DateTime($dup['dead_date_death']);
                                                echo $date->format('m/d/Y');
                                            } else {
                                                echo 'N/A';
                                            }
                                        }
                                    }
                                    ?>",
                        "Years Buried": "<?php
                                            if ($duplicate = mysqli_query($mysqli, $sql)) {
                                                while ($dup = mysqli_fetch_assoc($duplicate)) {
                                                    $current_date = new DateTime();
                                                    $death_date = new DateTime($dup['dead_date_death']);
                                                    $interval = $current_date->diff($death_date);
                                                    $years_buried = $interval->y;
                                                    $months_buried = $interval->m;

                                                    if ($years_buried < 1 && $months_buried < 12) {
                                                        echo 'Less than a year' . "<br>";
                                                    } else {
                                                        echo $years_buried . ' year(s) ' . $months_buried . ' months' . "<br>";
                                                    }
                                                }
                                            }
                                            ?>",
                        "Block": "<?php echo $row['block']; ?>",
                        "Status": "<?php echo $row['status']; ?>",
                        "Photos": "<?php
                                    $counter = 0;
                                    $sql = "SELECT * FROM tbl_files WHERE record_id = $graveno";
                                    if ($duplicate = mysqli_query($mysqli, $sql)) {
                                        while ($dup = mysqli_fetch_assoc($duplicate)) {
                                            $image_url = htmlspecialchars($dup['grave_filename'], ENT_QUOTES);
                                            echo "<a href='$image_url' target='_blank'><img src='$image_url' class='grave-photo' alt='Grave photo' class='grave-photo' alt='Grave photo'></a>";
                                            $counter++;
                                        }
                                    }
                                    ?>",
                        "PhotoCount": "<?php echo $counter; ?>",
                        "auxiliary_storage_labeling_offsetquad": "<?php echo $row['label']; ?>"
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [<?php $trim = str_replace('""', '', $row['coordinates']);
                                        echo $trim; ?>]
                    }
                },
            <?php
            }
            ?>
        ]
    }
</script>
<script>
    const userType = '<?php echo isset($_SESSION['user_type']) && $_SESSION['user_type'] !== null ? $_SESSION['user_type'] : 'user'; ?>';

    var map = L.map('displayUserCemeteryMap', {
        zoomControl: false,
        maxZoom: 20,
        minZoom: 1
    }).fitBounds([
        [10.251298497028595, 123.79519034872163],
        [10.251931801181197, 123.79637365545214]
    ]);
    var hash = new L.Hash(map);
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
    var bounds_group = new L.featureGroup([]);

    function setBounds() {}
    map.createPane('pane_GoogleSatellite_0');
    map.getPane('pane_GoogleSatellite_0').style.zIndex = 100;
    var layer_GoogleSatellite_0 = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        pane: 'pane_GoogleSatellite_0',
        opacity: 1.0,
        attribution: '',
        minZoom: 1,
        maxZoom: 20,
        minNativeZoom: 0,
        maxNativeZoom: 18
    });
    layer_GoogleSatellite_0;
    map.addLayer(layer_GoogleSatellite_0);

    function pop_new_location_1(feature, layer) {
        var popupContent = '<table>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['id'] !== null ? autolinker.link(feature.properties['id'].toLocaleString()) : '') + '</td>\
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

    function style_new_location_1_0() {
        return {
            pane: 'pane_new_location_1',
            opacity: 1,
            color: 'rgba(35,35,35,1.0)',
            dashArray: '',
            lineCap: 'butt',
            lineJoin: 'miter',
            weight: 1.0,
            fill: true,
            fillOpacity: .6,
            fillColor: '#858796',
            interactive: false,
        }
    }
    map.createPane('pane_new_location_1');
    map.getPane('pane_new_location_1').style.zIndex = 401;
    map.getPane('pane_new_location_1').style['mix-blend-mode'] = 'normal';
    var layer_new_location_1 = new L.geoJson(json_new_location_1, {
        attribution: '',
        interactive: false,
        dataVar: 'json_new_location_1',
        layerName: 'layer_new_location_1',
        pane: 'pane_new_location_1',
        onEachFeature: pop_new_location_1,
        style: style_new_location_1_0,
    });
    bounds_group.addLayer(layer_new_location_1);
    map.addLayer(layer_new_location_1);

    function pop_new_roads_2(feature, layer) {
        var popupContent = '<table>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['id'] !== null ? autolinker.link(feature.properties['id'].toLocaleString()) : '') + '</td>\
                    </tr>\
                </table>';
    }

    function style_new_roads_2_0() {
        return {
            pane: 'pane_new_roads_2',
            opacity: 1,
            color: 'rgba(239,229,192,1.0)',
            dashArray: '',
            lineCap: 'square',
            lineJoin: 'bevel',
            weight: 12.0,
            fillOpacity: 0,
            interactive: false,
        }
    }
    map.createPane('pane_new_roads_2');
    map.getPane('pane_new_roads_2').style.zIndex = 402;
    map.getPane('pane_new_roads_2').style['mix-blend-mode'] = 'normal';
    var layer_new_roads_2 = new L.geoJson(json_new_roads_2, {
        attribution: '',
        interactive: false,
        dataVar: 'json_new_roads_2',
        layerName: 'layer_new_roads_2',
        pane: 'pane_new_roads_2',
        onEachFeature: pop_new_roads_2,
        style: style_new_roads_2_0,
    });
    bounds_group.addLayer(layer_new_roads_2);
    map.addLayer(layer_new_roads_2);


    function getButtons(feature) {
        return `
            <div class="buttons">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== null && $_SESSION['user_type'] != 'admin') { ?>                
                 ${(feature.properties['Status'] === 'vacant') ? `
                <span class="view-plot-btn">
                    <a href="index.php?stat=${feature.properties['Status'] !== null ? feature.properties['Status'] : ''}&graveno=${feature.properties['Grave No.'] !== null ? feature.properties['Grave No.'] : ''}&page=book">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Plot Details">
                        <i class="bi bi-eye"></i>
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

    function private(feature) {
        return `
        <div class="popup-header">
            <div class="header-title">
                <span class="popup-title1">CemeterEase</span> <br>
                <span class="popup-title">Grave Details</span>
            </div>
        </div>
        <div class="info-grid">
            ${getLocationSection(feature)}
        </div>

        <div class="timeline-grid">
          <center>
            <strong style="color: red;"><i class="bi bi-exclamation-diamond me-2"></i>This plot is private</strong>
          </center>
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

    function pop_pointsfinall_3(feature, layer) {
        // Check DeceasedCount with default value of 0
        const deceasedCount = feature.properties['DeceasedCount'] || 0;
        const graveStatus = feature.properties['Status'];
        const visibility = feature.properties['Visibility'];
        const popupContent =
            //Display empty template if visibility is private and if the user type is 'user'
            (visibility === 'private') ? private(feature) :
            //Display reserved template if grave status is 'reserved'
            (graveStatus === 'reserved') ? getReservedTemplate(feature) :
            //Display empty template if the deceased count is less than 1 or the grave status is vacant
            (deceasedCount < 1 || graveStatus === 'vacant') ? empty(feature) :
            //Display multiple grave template if the deceased count is greater than 1
            (deceasedCount > 1) ? getMultipleGraveTemplate(feature) :
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
                window.navigateToGrave = function(lat, lng) {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser');
                        return;
                    }
                    
                    navigator.geolocation.getCurrentPosition(position => {
                        const destination = lat + ',' + lng;
                        const isAppleDevice = /(iPhone|iPad|iPod)/.test(navigator.platform);
                        
                        const url = isAppleDevice
                            ? 'maps://maps.apple.com/maps?daddr=' + destination
                            : 'https://www.google.com/maps/dir/?api=1&destination=' + destination;
                            
                        window.open(url);
                    });
                }`;
        document.head.appendChild(script);
    }

    function addStyles() {
        const style = document.createElement('style');
        style.textContent = `
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
            }`;
        document.head.appendChild(style);
    }

    var defaultView = {
        zoom: 28, // Set your default zoom level
        center: [10.251615149104896, 123.79578200208688], // Average of the bounds coordinates
        minZoom: 1,
        maxZoom: 20
    };

    // Add custom CSS
    const style = document.createElement('style');
    style.textContent = `
            .refresh-button {
                background-color: #fff;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #666;
                transition: all 0.3s ease;
            }
            .refresh-button:hover {
                background-color: #f4f4f4;
                color: #000;
            }
        `;
    document.head.appendChild(style);

    // Add control to map
    function style_pointsfinall_3_0(feature) {
        const visibility = String(feature.properties['Visibility']);
        const status = String(feature.properties['Status']);
        // if (userType === 'user' && visibility !== 'public' && status !== 'reserved') {
        //     return {
        //         pane: 'pane_pointsfinall_3',
        //         radius: 8.0,
        //         opacity: 1,
        //         color: 'rgba(0, 0, 0, 0.5)',
        //         dashArray: '',
        //         lineCap: 'round',
        //         lineJoin: 'round',
        //         weight: 2.0,
        //         fill: true,
        //         fillOpacity: 0.8,
        //         fillColor: '#00FF00',
        //         interactive: true,
        //     };
        // }
        switch (status) {
            case 'occupied1':
                return {
                    pane: 'pane_pointsfinall_3',
                        radius: 8.0,
                        opacity: 1,
                        color: 'rgba(0, 0, 0, 0.5)',
                        weight: 2.0,
                        fill: true,
                        fillOpacity: 0.8,
                        fillColor: '#FF0000',
                        interactive: true,
                };
            case 'vacant':
                return {
                    pane: 'pane_pointsfinall_3',
                        radius: 8.0,
                        opacity: 1,
                        color: 'rgba(0, 0, 0, 0.5)',
                        weight: 2.0,
                        fill: true,
                        fillOpacity: 0.8,
                        fillColor: '#00FF00',
                        interactive: true,
                };
            case 'occupied2':
                return {
                    pane: 'pane_pointsfinall_3',
                        radius: 8.0,
                        opacity: 1,
                        color: 'rgba(0, 0, 0, 0.5)',
                        weight: 2.0,
                        fill: true,
                        fillOpacity: 0.8,
                        fillColor: '#FFA500',
                        interactive: true,
                };
            case 'occupied3':
                return {
                    pane: 'pane_pointsfinall_3',
                        radius: 8.0,
                        opacity: 1,
                        color: 'rgba(0, 0, 0, 0.5)',
                        weight: 2.0,
                        fill: true,
                        fillOpacity: 0.8,
                        fillColor: '#800080',
                        interactive: true,
                };
            case 'reserved':
                return {
                    pane: 'pane_pointsfinall_3',
                        radius: 8.0,
                        opacity: 1,
                        color: 'rgba(0, 0, 0, 0.5)',
                        weight: 2.0,
                        fill: true,
                        fillOpacity: 0.8,
                        fillColor: '#ffc107',
                        interactive: true,
                };
            default:
                return {};
        }
    }

    map.createPane('pane_pointsfinall_3');
    map.getPane('pane_pointsfinall_3').style.zIndex = 403;
    map.getPane('pane_pointsfinall_3').style['mix-blend-mode'] = 'normal';
    var layer_pointsfinall_3 = new L.geoJson(json_pointsfinall_3, {
        attribution: '',
        interactive: true,
        dataVar: 'json_pointsfinall_3',
        layerName: 'layer_pointsfinall_3',
        pane: 'pane_pointsfinall_3',
        onEachFeature: pop_pointsfinall_3,
        pointToLayer: function(feature, latlng) {
            var context = {
                feature: feature,
                variables: {}
            };
            return L.circleMarker(latlng, style_pointsfinall_3_0(feature));
        },
    });
    bounds_group.addLayer(layer_pointsfinall_3);
    map.addLayer(layer_pointsfinall_3);
    setBounds();

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
            const deceasedName = String(props.deceased_fname || '').toLowerCase() + ' ' + String(props.deceased_lname || '').toLowerCase();
            
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
            const deceasedName = String(props.deceased_fname || '').toLowerCase() + ' ' + String(props.deceased_lname || '').toLowerCase();
            
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

    resetLabels([layer_pointsfinall_3]);
    map.on("zoomend", function() {
        resetLabels([layer_pointsfinall_3]);
    });
    map.on("layeradd", function() {
        resetLabels([layer_pointsfinall_3]);
    });
    map.on("layerremove", function() {
        resetLabels([layer_pointsfinall_3]);
    });


</script>