<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvestUz Map Not auth</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        /* Main styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        #map {
            height: calc(100vh - 50px);
            width: 100%;
            z-index: 1;
        }

        /* Header styles */
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 50px;
            padding: 0 15px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 2;
            position: relative;
        }

        .app-logo {
            display: flex;
            align-items: center;
            color: #1E3685;
        }

        .app-title {
            margin-left: 10px;
            font-weight: 600;
            font-size: 18px;
        }

        .lang-switcher {
            display: flex;
            gap: 5px;
        }

        .lang-btn {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: #f5f5f5;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            color: #333;
        }

        .lang-btn.active {
            background: #1E3685;
            color: white;
            border-color: #1E3685;
        }

        /* Flag decoration */
        .flag-decoration {
            height: 3px;
            width: 100%;
            display: flex;
            z-index: 2;
        }

        .flag-blue {
            flex: 1;
            background-color: #0099CC;
        }

        .flag-red {
            flex: 1;
            background-color: #CC0000;
        }

        .flag-green {
            flex: 1;
            background-color: #009933;
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 380px;
            height: 100vh;
            background: white;
            box-shadow: -3px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: right 0.3s ease;
            overflow-y: auto;
            padding-bottom: 20px;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1E3685;
            color: white;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 18px;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-content {
            padding: 15px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            color: #1E3685;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 6px 0;
            color: #333;
            font-size: 14px;
        }

        .details-table td:first-child {
            font-weight: 500;
            width: 40%;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background-color: #e6f7f1;
            color: #0E6245;
        }

        .badge-warning {
            background-color: #fff8e6;
            color: #F5A623;
        }

        .badge-info {
            background-color: #e6f2ff;
            color: #1E3685;
        }

        .documents-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .doc-group h4 {
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }

        .document-link {
            display: flex;
            align-items: center;
            color: #1E3685;
            text-decoration: none;
            padding: 8px;
            border-radius: 4px;
            background: #f5f5f5;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .document-link i {
            margin-right: 8px;
        }

        .document-link:hover {
            background: #e6f2ff;
        }

        .additional-info {
            font-size: 14px;
            line-height: 1.5;
            color: #555;
            white-space: pre-line;
        }

        /* Related investments section */
        .related-investments {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .data-card {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #eee;
            background: #f9f9f9;
            cursor: pointer;
            transition: all 0.2s;
        }

        .data-card:hover {
            background: #f0f7ff;
            border-color: #1E3685;
        }

        .data-card-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #1E3685;
        }

        /* Loading indicator */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #1E3685;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            z-index: 1002;
            opacity: 0.9;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .toast.info {
            background: #1E3685;
        }

        .toast.warning {
            background: #F5A623;
        }

        .toast.error {
            background: #D62839;
        }

        /* Popup customization */
        .leaflet-popup-content {
            margin: 10px 12px;
        }

        .leaflet-popup-content h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .leaflet-popup-content p {
            margin: 3px 0;
            font-size: 14px;
        }

        .details-btn {
            margin-top: 8px;
            padding: 5px 10px;
            background: #1E3685;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 13px;
        }

        .details-btn:hover {
            background: #152a6c;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                right: -100%;
            }

            .app-title {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <header class="app-header">
        <div class="app-logo">
            <i class="fas fa-map-marked-alt fa-2x"></i>
            <div class="app-title">ИнвестУз - Инвестиция харитаси (Сайт тест режимида ишламоқда)</div>
        </div>
        <div class="lang-switcher">
            <button class="lang-btn active">УЗ</button>
            <button class="lang-btn">RU</button>
            <a class="lang-btn" href="{{ route('login') }}">Login</a>
        </div>
    </header>

    <!-- Flag Decoration -->
    <div class="flag-decoration">
        <div class="flag-blue"></div>
        <div class="flag-red"></div>
        <div class="flag-green"></div>
    </div>

    <div id="map"></div>
    <div id="loading" class="loading">
        <div class="spinner"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- KMZ Support -->
    <script src="https://unpkg.com/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-omnivore/0.3.4/leaflet-omnivore.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/togeojson/0.16.0/togeojson.min.js"></script>

    <script>
        // App namespace
        const App = {
            map: null,
            markers: [],
            polygons: {},
            kmzLayers: {}, // Store KMZ layers
            markerCluster: null,
            currentSidebar: null,
            isAnimating: false,
            currentItem: null,
            lastView: {
                center: null,
                zoom: null
            },
            cleanup: [],
            apiBaseUrl: (function() {
                const hostname = window.location.hostname;
                if (hostname === 'localhost' || hostname === '127.0.0.1') {
                    return 'http://127.0.0.1:8000';
                } else {
                    return 'https://development.toshkentinvest.uz';
                }
            })()
        };


        // Show loading indicator
        function showLoading() {
            document.getElementById('loading').style.display = 'flex';
        }

        // Hide loading indicator
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Convert DMS coordinates to decimal
        function dmsToDecimal(dmsStr) {
            if (!dmsStr) return null;

            // Example format: "41°19'7.54"С"
            const regex = /(\d+)°(\d+)'(\d+\.\d+)"([СNЮSВEЗW])/;
            const match = dmsStr.match(regex);

            if (!match) return null;

            const degrees = parseFloat(match[1]);
            const minutes = parseFloat(match[2]);
            const seconds = parseFloat(match[3]);
            const direction = match[4];

            let decimal = degrees + (minutes / 60) + (seconds / 3600);

            // If south or west, negate the value
            if (['Ю', 'S', 'З', 'W'].includes(direction)) {
                decimal *= -1;
            }

            return decimal;
        }

        // Format status
        function formatStatus(status) {
            if (!status) {
                return {
                    text: "Статус не указан",
                    class: "badge-info"
                };
            }

            switch (status) {
                case "9":
                    return {
                        text: "Инвест договор", class: "badge-success"
                    };
                case "1":
                    return {
                        text: "Ишлаб чиқилмоқда", class: "badge-warning"
                    };
                case "2":
                    return {
                        text: "Қурилиш жараёнида", class: "badge-info"
                    };
                default:
                    return {
                        text: "Статус: " + status, class: "badge-info"
                    };
            }
        }

        // Initialize map
        function initMap() {
            // Create map - centered on Tashkent
            App.map = L.map('map', {
                center: [41.311, 69.279],
                zoom: 11,
                minZoom: 10,
                maxZoom: 18
            });

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(App.map);

            // Create marker cluster
            App.markerCluster = L.markerClusterGroup({
                chunkedLoading: true,
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                disableClusteringAtZoom: 16
            });

            App.map.addLayer(App.markerCluster);
        }

        // Extract polygon coordinates
        function extractPolygonCoordinates(polygonData) {
            if (!polygonData) {
                return null;
            }

            let coordinates = [];

            // Handle different polygon data structures
            if (Array.isArray(polygonData)) {
                if (polygonData.length < 3) {
                    return null; // Need at least 3 points to form a polygon
                }

                // Check if array contains objects with lat/lng properties
                if (typeof polygonData[0] === 'object') {
                    // Format: [{start_lat, start_lon}, ...]
                    if (polygonData[0].start_lat && polygonData[0].start_lon) {
                        coordinates = polygonData.map(point => {
                            const lat = dmsToDecimal(point.start_lat);
                            const lng = dmsToDecimal(point.start_lon);

                            if (lat === null || lng === null) {
                                return null;
                            }

                            return [lat, lng];
                        }).filter(coord => coord !== null);
                    }
                    // Format: [{lat, lng}, ...]
                    else if (polygonData[0].lat && polygonData[0].lng) {
                        coordinates = polygonData.map(point => {
                            return [parseFloat(point.lat), parseFloat(point.lng)];
                        });
                    }
                    // Format: [{latitude, longitude}, ...]
                    else if (polygonData[0].latitude && polygonData[0].longitude) {
                        coordinates = polygonData.map(point => {
                            return [parseFloat(point.latitude), parseFloat(point.longitude)];
                        });
                    }
                    // Format: [[lat, lng], ...]
                    else if (Array.isArray(polygonData[0]) && polygonData[0].length === 2) {
                        coordinates = polygonData.map(point => {
                            return [parseFloat(point[0]), parseFloat(point[1])];
                        });
                    }
                }
                // Format: [lat1, lng1, lat2, lng2, ...]
                else if (typeof polygonData[0] === 'number' && polygonData.length >= 6 && polygonData.length % 2 === 0) {
                    for (let i = 0; i < polygonData.length; i += 2) {
                        coordinates.push([parseFloat(polygonData[i]), parseFloat(polygonData[i + 1])]);
                    }
                }
            }
            // GeoJSON-like structure
            else if (polygonData.type === 'Polygon' && Array.isArray(polygonData.coordinates)) {
                if (polygonData.coordinates.length > 0 && Array.isArray(polygonData.coordinates[0])) {
                    coordinates = polygonData.coordinates[0].map(coord => {
                        // GeoJSON coordinates are [lng, lat], we need to flip them
                        return [parseFloat(coord[1]), parseFloat(coord[0])];
                    });
                }
            }

            // Ensure we have at least 3 valid coordinates for a polygon
            if (coordinates.length < 3) {
                console.warn('Not enough valid coordinates found in polygon data');
                return null;
            }

            return coordinates;
        }

        // Add marker to map
        function addMarker(lot) {
            if (!lot || !lot.lat || !lot.lng) {
                return false;
            }

            // Create marker
            const marker = L.marker([lot.lat, lot.lng]);

            // Generate an ID if not present
            if (!lot.id) {
                lot.id = 'lot-' + Math.random().toString(36).substr(2, 9);
            }

            // Store lot ID directly on marker object
            marker.lotId = lot.id;

            // Format name based on available data
            const name = lot.name || lot.neighborhood_name || 'Unnamed';
            const district = lot.district || lot.district_name || '';
            const area = lot.area || lot.area_hectare || '';
            const statusText = lot.status ? formatStatus(lot.status).text : 'Статус не указан';

            // Add popup
            const popup = `
    <div>
        <h3>${name}</h3>
        <p>${district}</p>
        <p>Майдон: ${area} га</p>
        <p>${statusText}</p>
        <button class="details-btn" data-lot-id="${lot.id}">Тафсилотлар</button>
    </div>
`;

            marker.bindPopup(popup);

            // Add click handler
            marker.on('click', function(e) {
                // Use the lotId property to identify which lot was clicked
                showDetails(this.lotId);
                L.DomEvent.stopPropagation(e);
            });

            // Add to cluster
            App.markerCluster.addLayer(marker);

            // Store reference
            App.markers.push({
                marker: marker,
                data: lot
            });

            return true;
        }

        // Add polygon to map
        function addPolygon(lot) {
            if (!lot || !lot.id || !lot.polygons) {
                return false;
            }

            // Extract coordinates
            const coords = extractPolygonCoordinates(lot.polygons);
            if (!coords) {
                return false;
            }

            // Determine style based on status
            let style = {
                color: '#1E3685',
                weight: 2,
                opacity: 0.7,
                fillColor: '#1E3685',
                fillOpacity: 0.2
            };

            if (lot.status === "9") {
                style.color = '#0E6245';
                style.fillColor = '#0E6245';
            } else if (lot.status === "2") {
                style.color = '#D62839';
                style.fillColor = '#D62839';
            }

            // Create polygon
            const polygon = L.polygon(coords, style);

            // Store lot ID directly on polygon object
            polygon.lotId = lot.id;

            // Add hover effect
            polygon.on('mouseover', function() {
                this.setStyle({
                    weight: 3,
                    fillOpacity: 0.4
                });
            });

            polygon.on('mouseout', function() {
                this.setStyle(style);
            });

            // Add click handler
            polygon.on('click', function(e) {
                // Use the lotId property to identify which lot was clicked
                showDetails(this.lotId);
                L.DomEvent.stopPropagation(e);
            });

            // Add to map
            polygon.addTo(App.map);

            // Store reference
            App.polygons[lot.id] = {
                polygon: polygon,
                data: lot
            };

            return true;
        }

// Process KMZ files - FIXED VERSION
async function processKmzFile(lot, kmzDoc) {
    if (!lot || !lot.id || !kmzDoc) {
        console.error('Invalid lot or KMZ document data');
        return false;
    }

    try {
        // Fix the URL path by making it relative to the current domain
        let kmzUrl = kmzDoc.url;

        // Check if URL is absolute and doesn't match current domain
        if (kmzUrl.startsWith('http') && !kmzUrl.includes(window.location.hostname)) {
            const paths = kmzUrl.split('/assets/');
            if (paths.length > 1) {
                kmzUrl = App.apiBaseUrl + '/assets/data/BASA_RENOVA/' + paths[1].split('/').pop();
            }
        }

        console.log(`Processing KMZ file: ${kmzUrl} for lot ID: ${lot.id}`);

        // Check if we already have a layer for this KMZ
        if (App.kmzLayers[kmzUrl]) {
            console.log('KMZ layer already exists, adding to lot');
            // Update the lot ID for this layer
            App.kmzLayers[kmzUrl].lotId = lot.id;
            // Also store lot data in the layer for direct access
            App.kmzLayers[kmzUrl].lotData = lot;
            return true;
        }

        // Fetch the KMZ file
        const response = await fetch(kmzUrl);
        if (!response.ok) {
            throw new Error(`Failed to fetch KMZ file: ${response.statusText}`);
        }

        const kmzData = await response.arrayBuffer();
        const zip = await JSZip.loadAsync(kmzData);

        // Find the KML file in the KMZ archive
        let kmlFile;
        let kmlContent;

        // Look for doc.kml or any .kml file in the root of the zip
        if (zip.file('doc.kml')) {
            kmlFile = zip.file('doc.kml');
        } else {
            // Try to find any KML file
            const kmlFiles = Object.keys(zip.files).filter(filename =>
                filename.toLowerCase().endsWith('.kml') && !zip.files[filename].dir
            );

            if (kmlFiles.length > 0) {
                kmlFile = zip.file(kmlFiles[0]);
            }
        }

        if (!kmlFile) {
            throw new Error('No KML file found in KMZ archive');
        }

        // Extract the KML content
        kmlContent = await kmlFile.async('text');

        // Parse the KML using toGeoJSON library
        const parser = new DOMParser();
        const kmlDoc = parser.parseFromString(kmlContent, 'text/xml');
        const geoJson = toGeoJSON.kml(kmlDoc);

        // Determine style based on lot status
        let style = {
            color: '#1E3685',
            weight: 2,
            opacity: 0.7,
            fillColor: '#1E3685',
            fillOpacity: 0.2
        };

        if (lot.status === "9") {
            style.color = '#0E6245';
            style.fillColor = '#0E6245';
        } else if (lot.status === "2") {
            style.color = '#D62839';
            style.fillColor = '#D62839';
        }

        // Create a GeoJSON layer from the parsed KML
        const kmzLayer = L.geoJSON(geoJson, {
            style: style,
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng);
            },
            onEachFeature: function(feature, layer) {
                // Add hover effect
                if (layer.setStyle) {
                    layer.on('mouseover', function() {
                        this.setStyle({
                            weight: 3,
                            fillOpacity: 0.4
                        });
                    });

                    layer.on('mouseout', function() {
                        this.setStyle(style);
                    });
                }

                // Add popup with any properties in the KML
                if (feature.properties && feature.properties.name) {
                    let popupContent = `<div><strong>${feature.properties.name}</strong>`;

                    if (feature.properties.description) {
                        popupContent += `<p>${feature.properties.description}</p>`;
                    }

                    popupContent +=
                        `<button class="details-btn" data-lot-id="${lot.id}">Тафсилотлар</button></div>`;

                    layer.bindPopup(popupContent);
                }

                // Add click handler to show lot details
                layer.on('click', function(e) {
                    showDetails(lot.id);
                    L.DomEvent.stopPropagation(e);
                });
            }
        });

        // Store lot ID and data on the layer for direct access
        kmzLayer.lotId = lot.id;
        kmzLayer.lotData = lot;

        // Add to map
        kmzLayer.addTo(App.map);

        // Store reference
        App.kmzLayers[kmzUrl] = kmzLayer;

        console.log(`Successfully processed KMZ file for lot ${lot.id}`);
        return true;

    } catch (error) {
        console.error(`Error processing KMZ file for lot ${lot.id}: ${error.message}`, error);
        return false;
    }
}

// Show details - FIXED VERSION
function showDetails(lotId) {
    // Validate ID
    if (!lotId) {
        console.error('Invalid lot ID');
        return;
    }

    // Prevent multiple animations
    if (App.isAnimating) {
        console.log('Animation in progress, ignoring request');
        return;
    }

    // Find lot data
    let lot = null;
    let markerEntry = null;
    let polygonEntry = null;
    let kmzLayerFound = null;

    // Search in markers
    for (let i = 0; i < App.markers.length; i++) {
        if (App.markers[i].data && App.markers[i].data.id === lotId) {
            markerEntry = App.markers[i];
            lot = markerEntry.data;
            console.log("Found lot in markers:", lot);
            break;
        }
    }

    // If not found in markers, check polygons
    if (!lot && App.polygons[lotId]) {
        polygonEntry = App.polygons[lotId];
        lot = polygonEntry.data;
        console.log("Found lot in polygons:", lot);
    }

    // If still not found, check KMZ layers
    if (!lot) {
        for (const url in App.kmzLayers) {
            const kmzLayer = App.kmzLayers[url];
            if (kmzLayer.lotId === lotId) {
                kmzLayerFound = kmzLayer;

                // If lotData is stored directly on the layer, use it
                if (kmzLayer.lotData) {
                    lot = kmzLayer.lotData;
                    console.log("Found lot data directly in KMZ layer:", lot);
                    break;
                }

                // Otherwise try to find it in markers or polygons
                for (let i = 0; i < App.markers.length; i++) {
                    if (App.markers[i].data && App.markers[i].data.id === lotId) {
                        lot = App.markers[i].data;
                        console.log("Found lot in markers via KMZ reference:", lot);
                        break;
                    }
                }

                if (!lot && App.polygons[lotId]) {
                    lot = App.polygons[lotId].data;
                    console.log("Found lot in polygons via KMZ reference:", lot);
                }

                break;
            }
        }
    }

    // Validate lot data
    if (!lot) {
        console.error(`Lot with ID ${lotId} not found in any data source`);
        // Look through all data sources to try to find the lot
        console.log("Available marker IDs:", App.markers.map(m => m.data.id));
        console.log("Available polygon IDs:", Object.keys(App.polygons));
        console.log("Available KMZ layer IDs:", Object.values(App.kmzLayers).map(l => l.lotId));
        return;
    }

    console.log(`Found lot:`, lot);

            // Store view state
            App.lastView.zoom = App.map.getZoom();
            App.lastView.center = App.map.getCenter();

            // Close existing sidebar
            closeSidebar(true);

            // Set current item and animation state
            App.currentItem = lotId;
            App.isAnimating = true;

            // Get status info
            const status = lot.status ? formatStatus(lot.status) : {
                text: "Статус не указан",
                class: "badge-info"
            };

            // Format fields based on the API response structure
            const name = lot.name || lot.neighborhood_name || 'Unnamed';
            const district = lot.district || lot.district_name || 'N/A';
            const area = lot.area || lot.area_hectare || 'N/A';
            const strategy = lot.strategy || lot.area_strategy || 'N/A';
            const decision = lot.decision || lot.decision_number || 'N/A';
            const cadastre = lot.cadastre || lot.cadastre_certificate || 'N/A';
            const floors = lot.floors || lot.designated_floors || lot.proposed_floors || 'N/A';
            const kmn = lot.kmn || lot.qmn_percentage || 'N/A';
            const umn = lot.umn || lot.umn_coefficient || 'N/A';
            const residential = lot.residential_area || lot.residential || 'N/A';
            const nonResidential = lot.non_residential_area || lot.nonResidential || 'N/A';
            const totalArea = lot.total_building_area || lot.total || 'N/A';
            const investor = lot.investor || 'N/A';
            const population = lot.population || 'N/A';
            const household = lot.household_count || 'N/A';
            const additionalInfo = lot.additional_information || 'N/A';

// Create sidebar
            const sidebar = document.createElement('div');
            sidebar.className = 'sidebar';
            sidebar.id = `sidebar-${Date.now()}`;

            // Generate sidebar HTML with our structure - Uzbek Cyrillic
            let sidebarHtml = `
                <div class="sidebar-header">
                    <h2>${name}</h2>
                    <button class="sidebar-close-btn">×</button>
                </div>
                <div class="sidebar-content">`;

            sidebarHtml += `
                    <div class="section-title">Асосий маълумотлар</div>
                    <table class="details-table">
                        <tr><td>Туман:</td><td>${district}</td></tr>
                        <tr><td>Майдон:</td><td>${area} га</td></tr>
                        <tr><td>Ҳолати:</td><td><span class="badge ${status.class}">${status.text}</span></td></tr>
                        <tr><td>Стратегия:</td><td>${strategy}</td></tr>
                        <tr><td>Қарор:</td><td>${decision}</td></tr>
                    </table>

                    <div class="section-title">Техник параметрлар</div>
                    <table class="details-table">
                        <tr><td>Кадастр:</td><td>${cadastre}</td></tr>
                        <tr><td>Қаватлар:</td><td>${floors}</td></tr>
                        <tr><td>КМН:</td><td>${kmn}</td></tr>
                        <tr><td>УМН:</td><td>${umn}</td></tr>
                    </table>

                    <div class="section-title">Майдонлар</div>
                    <table class="details-table">
                        <tr><td>Турар жой:</td><td>${residential} м²</td></tr>
                        <tr><td>Нотурар жой:</td><td>${nonResidential} м²</td></tr>
                        <tr><td>Умумий:</td><td>${totalArea} м²</td></tr>
                    </table>`;

            // Add investor information if available
            if (investor !== 'N/A' && investor !== '0') {
                sidebarHtml += `
                    <div class="section-title">Инвестор</div>
                    <table class="details-table">
                        <tr><td>Исм:</td><td>${investor}</td></tr>
                    </table>
                `;
            }

            // Add demographic information if available
            if (population !== 'N/A' || household !== 'N/A') {
                sidebarHtml += `
                    <div class="section-title">Демографик маълумотлар</div>
                    <table class="details-table">
                        ${population !== 'N/A' ? `<tr><td>Аҳоли сони:</td><td>${population}</td></tr>` : ''}
                        ${household !== 'N/A' ? `<tr><td>Хонадонлар сони:</td><td>${household}</td></tr>` : ''}
                    </table>
                `;
            }

            // Add documents if available
            if (lot.documents && lot.documents.length > 0) {
                sidebarHtml += `
                    <div class="section-title">Ҳужжатлар</div>
                    <div class="documents-list">`;

                // Group documents by type
                const pdfDocs = lot.documents.filter(doc => doc.doc_type === 'pdf-document');
                const kmzDocs = lot.documents.filter(doc => doc.doc_type === 'kmz-document');



                // Add KMZ documents
                if (kmzDocs.length > 0) {
                    sidebarHtml += `<div class="doc-group">
                        <h4>KMZ Харита файллари</h4>`;

                    kmzDocs.forEach(doc => {
                        const fileName = doc.filename || 'KMZ файл';
                        // Fix URL to use the apiBaseUrl
                        let kmzUrl = doc.url;
                        if (kmzUrl.startsWith('http') && !kmzUrl.includes(window.location.hostname)) {
                            const paths = kmzUrl.split('/assets/');
                            if (paths.length > 1) {
                                kmzUrl = App.apiBaseUrl + '/assets/data/BASA_RENOVA/' + paths[1].split('/').pop();
                            }
                        }

                        sidebarHtml += `
                            <a href="${kmzUrl}" download class="document-link">
                                <i class="fas fa-map"></i> ${fileName}
                            </a>`;
                    });

                    sidebarHtml += `</div>`;
                }

                sidebarHtml += `</div>`;
            }

            // Add additional information if available
            if (additionalInfo !== 'N/A') {
                sidebarHtml += `
                    <div class="section-title">Қўшимча маълумотлар</div>
                    <div class="additional-info">${additionalInfo}</div>
                `;
            }

            // Close the content div
            sidebarHtml += `</div>`;

            // Set the sidebar HTML
            sidebar.innerHTML = sidebarHtml;

            // Add to body
            document.body.appendChild(sidebar);
            App.currentSidebar = sidebar;

            // Add close button event
            const closeBtn = sidebar.querySelector('.sidebar-close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeSidebar();
                });
            }

            showToast('Details loaded successfully');

            // Animation sequence
            requestAnimationFrame(() => {
                // Open sidebar
                sidebar.classList.add('open');

                // Wait for sidebar animation
                setTimeout(() => {
                    // Stop any existing map animations
                    App.map.stop();

                    // Highlight the feature
                    if (polygonEntry && polygonEntry.polygon) {
                        highlightPolygon(polygonEntry.polygon, lot.id);
                        adjustPolygonView(polygonEntry.polygon);
                    } else if (markerEntry && markerEntry.marker) {
                        highlightMarker(markerEntry.marker, lot.id);
                        adjustMarkerView(markerEntry.marker);
                    } else {
                        // Try to find and focus on KMZ layer if it exists
                        for (const url in App.kmzLayers) {
                            if (App.kmzLayers[url].lotId === lot.id) {
                                highlightKmzLayer(App.kmzLayers[url], lot.id);
                                adjustKmzLayerView(App.kmzLayers[url]);
                                break;
                            }
                        }
                    }

                    // Add related investments
                    addRelatedInvestments(sidebar, lot);

                    // Reset animation state
                    setTimeout(() => {
                        App.isAnimating = false;
                    }, 1000);
                }, 300);
            });
        }

        // Highlight polygon
        function highlightPolygon(polygon, lotId) {
            if (!polygon) return;

            // Store original style
            const originalStyle = {
                ...polygon.options
            };

            // Pulse effect
            const pulseHighlight = () => {
                polygon.setStyle({
                    weight: 4,
                    color: '#4A6FD4',
                    dashArray: '5, 10',
                    fillOpacity: 0.5
                });

                setTimeout(() => {
                    polygon.setStyle(originalStyle);

                    setTimeout(() => {
                        // Only continue if still the current item
                        if (App.currentItem === lotId) {
                            pulseHighlight();
                        } else {
                            // If no longer current, stop the pulse
                            return;
                        }
                    }, 1500);
                }, 700);
            };

            // Start the pulse
            pulseHighlight();

            // Add the cleanup function to stop highlighting when sidebar is closed
            App.cleanup.push(() => {
                polygon.setStyle(originalStyle);
            });
        }

        // Highlight KMZ layer
        function highlightKmzLayer(kmzLayer, lotId) {
            if (!kmzLayer) return;

            // Store all original styles for each feature in the layer
            const originalStyles = [];

            // Apply highlighting to all features in the layer
            kmzLayer.eachLayer(function(layer) {
                if (layer.setStyle) {
                    // Store the original style
                    originalStyles.push({
                        layer: layer,
                        style: {
                            ...layer.options
                        }
                    });

                    // Apply highlight style
                    layer.setStyle({
                        weight: 4,
                        color: '#4A6FD4',
                        dashArray: '5, 10',
                        fillOpacity: 0.5
                    });
                }
            });

            // Add cleanup function to restore original styles
            App.cleanup.push(() => {
                originalStyles.forEach(item => {
                    item.layer.setStyle(item.style);
                });
            });
        }

        // Highlight marker
        function highlightMarker(marker, lotId) {
            if (!marker) return;
            // You could add marker highlight effects here if needed
        }

        // Adjust polygon view
        function adjustPolygonView(polygon) {
            if (!polygon) return;

            const bounds = polygon.getBounds();
            App.map.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 17,
                animate: true
            });
        }

        // Adjust KMZ layer view
        function adjustKmzLayerView(kmzLayer) {
            if (!kmzLayer) return;

            try {
                const bounds = kmzLayer.getBounds();
                App.map.fitBounds(bounds, {
                    padding: [50, 50],
                    maxZoom: 17,
                    animate: true
                });
            } catch (error) {
                console.error('Error adjusting KMZ layer view:', error);
                // If bounds can't be determined, try to zoom to a layer feature
                let featureFound = false;

                kmzLayer.eachLayer(function(layer) {
                    if (!featureFound && layer.getLatLng) {
                        App.map.setView(layer.getLatLng(), 17, {
                            animate: true
                        });
                        featureFound = true;
                    } else if (!featureFound && layer.getBounds) {
                        App.map.fitBounds(layer.getBounds(), {
                            padding: [50, 50],
                            maxZoom: 17,
                            animate: true
                        });
                        featureFound = true;
                    }
                });
            }
        }

        // Adjust marker view
        function adjustMarkerView(marker) {
            if (!marker) return;

            const latLng = marker.getLatLng();
            App.map.setView(latLng, 17, {
                animate: true
            });
        }

        // Add related investments
        function addRelatedInvestments(sidebar, lot) {
            if (!sidebar || !lot) return;

            // Get the district from the lot data
            const district = lot.district || lot.district_name;
            if (!district) return;

            const sidebarContent = sidebar.querySelector('.sidebar-content');
            if (!sidebarContent) return;

            // Find related investments
            const related = App.markers
                .filter(m => {
                    // Get the district from each marker's data
                    const markerDistrict = m.data.district || m.data.district_name;
                    return m.data.id !== lot.id && markerDistrict === district;
                })
                .slice(0, 3);

            if (related.length === 0) return;

            // Create section
            const section = document.createElement('div');

            let html = `
                <div class="section-title">Боғлиқ инвестициялар</div>
                <div class="related-investments">
            `;

            related.forEach(({
                data
            }) => {
                const name = data.name || data.neighborhood_name || 'Unnamed';
                const district = data.district || data.district_name || '';
                const area = data.area || data.area_hectare || '';

                html += `
                    <div class="data-card" data-lot-id="${data.id}">
                        <div class="data-card-title">${name}</div>
                        <div>${district}</div>
                        <div>Area: ${area} га</div>
                    </div>
                `;
            });

            html += '</div>';
            section.innerHTML = html;
            sidebarContent.appendChild(section);

            // Add click handlers
            const cards = section.querySelectorAll('.data-card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.getAttribute('data-lot-id');
                    if (id) {
                        setTimeout(() => {
                            showDetails(id);
                        }, 100);
                    }
                });
            });
        }

        // Close sidebar
        function closeSidebar(immediate = false) {
            if (!App.currentSidebar) return;

            if (immediate) {
                // Run cleanup functions
                if (App.cleanup && App.cleanup.length) {
                    App.cleanup.forEach(fn => {
                        try {
                            fn();
                        } catch (e) {
                            console.error('Cleanup error:', e);
                        }
                    });
                    App.cleanup = [];
                }

                // Remove sidebar immediately
                App.currentSidebar.remove();
                App.currentSidebar = null;
                App.currentItem = null;
                return;
            }

            // Normal animated close
            App.currentSidebar.classList.remove('open');

            setTimeout(() => {
                // Run cleanup
                if (App.cleanup && App.cleanup.length) {
                    App.cleanup.forEach(fn => {
                        try {
                            fn();
                        } catch (e) {
                            console.error('Cleanup error:', e);
                        }
                    });
                    App.cleanup = [];
                }

                // Remove sidebar
                App.currentSidebar.remove();
                App.currentSidebar = null;
                App.currentItem = null;

                // Restore previous view
                if (App.lastView.center && App.lastView.zoom) {
                    App.map.setView(App.lastView.center, App.lastView.zoom, {
                        animate: true
                    });
                }
            }, 300);
        }

        // Fetch data from API
        async function fetchData() {
            showLoading();

            try {
                // Use the correct API endpoint
                const apiUrl = `${App.apiBaseUrl}/api/aktivs`;
                console.log(`Fetching data from: ${apiUrl}`);

                const response = await fetch(apiUrl);

                if (!response.ok) {
                    throw new Error(`API request failed with status ${response.status}`);
                }

                const data = await response.json();
                console.log('API response:', data);

                // Check if we have the lots array as expected from your API format
                let lotsData = [];

                if (data && data.lots && Array.isArray(data.lots)) {
                    // We have the expected data format with a 'lots' array
                    lotsData = data.lots;
                    console.log(`Found ${lotsData.length} lots in API response`);
                } else if (data && Array.isArray(data)) {
                    // The data is a direct array
                    lotsData = data;
                    console.log(`Found ${lotsData.length} lots in array response`);
                } else if (data && typeof data === 'object') {
                    // Try to find arrays in the response
                    let foundArray = false;

                    // Check for common array property names
                    const possibleArrayProps = ['data', 'items', 'results', 'features', 'objects'];

                    for (const prop of possibleArrayProps) {
                        if (data[prop] && Array.isArray(data[prop])) {
                            lotsData = data[prop];
                            foundArray = true;
                            console.log(`Found ${lotsData.length} items in '${prop}' property`);
                            break;
                        }
                    }

                    if (!foundArray) {
                        // If still not found, check all properties for arrays
                        for (const key in data) {
                            if (Array.isArray(data[key]) && data[key].length > 0) {
                                lotsData = data[key];
                                console.log(`Found ${lotsData.length} items in '${key}' property`);
                                break;
                            }
                        }
                    }

                    // If still no array found, try to convert object to array
                    if (lotsData.length === 0) {
                        // Convert object to array if it looks like an object of objects
                        const keys = Object.keys(data);

                        if (keys.length > 0 && typeof data[keys[0]] === 'object') {
                            lotsData = Object.values(data);
                            console.log(`Converted object to array with ${lotsData.length} items`);
                        } else if (Object.keys(data).length > 0) {
                            // If it's just a single object, wrap it in an array
                            lotsData = [data];
                            console.log('Wrapped single object in array');
                        }
                    }
                }

                if (lotsData.length === 0) {
                    console.warn('No data found in API response');
                    showToast('No data found in API response', 'warning');
                    return;
                }

                // Process the lots data
                let processedCount = 0;
                let processedKmzCount = 0;

                // Generate an ID counter for lots without IDs
                let idCounter = 1;

                // Track promises for KMZ processing
                const kmzPromises = [];

                lotsData.forEach(lot => {
                    // Skip invalid items
                    if (!lot || typeof lot !== 'object') {
                        return;
                    }

                    // Add an ID if missing
                    if (!lot.id) {
                        lot.id = 'lot-' + idCounter++;
                    }

                    // Add marker if coordinates exist
                    if (lot.lat && lot.lng) {
                        if (addMarker(lot)) {
                            processedCount++;
                        }
                    }

                    // Add polygon if available
                    if (lot.polygons) {
                        if (addPolygon(lot)) {
                            processedCount++;
                        }
                    }

                    // Process KMZ documents if available
                    if (lot.documents && Array.isArray(lot.documents)) {
                        const kmzDocs = lot.documents.filter(doc =>
                            doc.doc_type === 'kmz-document'
                        );

                        if (kmzDocs.length > 0) {
                            // Process the first KMZ document only to avoid overlapping polygons
                            const promise = processKmzFile(lot, kmzDocs[0])
                                .then(success => {
                                    if (success) {
                                        processedKmzCount++;
                                    }
                                })
                                .catch(error => {
                                    console.error(`Error processing KMZ for lot ${lot.id}:`, error);
                                });

                            kmzPromises.push(promise);
                        }
                    }
                });

                // Wait for all KMZ processing to complete
                await Promise.allSettled(kmzPromises);

                if (processedCount > 0 || processedKmzCount > 0) {
                    showToast(
                        `Successfully loaded ${processedCount} polygons/markers and ${processedKmzCount} KMZ files`,
                        'info'
                    );

                    // Fit map to all markers
                    if (App.markers.length > 0) {
                        const group = L.featureGroup(App.markers.map(m => m.marker));
                        App.map.fitBounds(group.getBounds(), {
                            padding: [50, 50]
                        });
                    }
                } else {
                    console.warn('No valid items were processed from API data');
                    showToast('No valid items found in data', 'warning');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                showToast('Error loading data: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Popup button clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('details-btn')) {
                    const lotId = e.target.getAttribute('data-lot-id');
                    if (lotId) {
                        showDetails(lotId);
                    }
                }
            });

            // Map click
            App.map.on('click', function() {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });

            // Window resize
            window.addEventListener('resize', function() {
                App.map.invalidateSize();
            });
        }

        // Initialize app
        async function init() {
            showLoading();

            try {
                // Initialize map
                initMap();

                // Setup event listeners
                setupEventListeners();

                // Fetch and process data
                await fetchData();

                // Check if data was loaded successfully
                if (App.markers.length === 0 &&
                    Object.keys(App.polygons).length === 0 &&
                    Object.keys(App.kmzLayers).length === 0) {
                    console.warn('No data loaded on map');
                    showToast('No map data found', 'warning');
                }
            } catch (error) {
                console.error('Initialization error:', error);
                showToast('Error initializing map: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        // Start the app when DOM is ready
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>

</html>
