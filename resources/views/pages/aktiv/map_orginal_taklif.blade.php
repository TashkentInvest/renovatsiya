<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Map - InvestUz</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">

    <!-- Application Styles -->
    <style>
        :root {
            --primary-color: #2b5797;
            --secondary-color: #0078d7;
            --accent-color: #0056b3;
            --success-color: #107c10;
            --warning-color: #ff8c00;
            --danger-color: #e81123;
            --light-gray: #f3f3f3;
            --medium-gray: #e0e0e0;
            --dark-gray: #767676;
            --text-color: #333333;
            --text-light: #666666;
            --white: #ffffff;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.1);
            --border-radius: 4px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.5;
            color: var(--text-color);
            background-color: var(--light-gray);
        }

        #map {
            width: 100%;
            height: 100vh;
            background: var(--light-gray);
            z-index: 1;
        }

        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: var(--primary-color);
            color: var(--white);
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
            box-shadow: var(--shadow-md);
        }

        .app-title {
            font-weight: 500;
            font-size: 20px;
            display: flex;
            align-items: center;
        }

        .app-title i {
            margin-right: 10px;
        }

        .loading {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading.active {
            display: flex;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(43, 87, 151, 0.2);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: var(--white);
            box-shadow: var(--shadow-lg);
            z-index: 1001;
            transition: var(--transition);
            overflow-y: auto;
            padding-top: 60px;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
            font-size: 18px;
            font-weight: 500;
            color: var(--primary-color);
        }

        .sidebar-header button {
            background: transparent;
            border: none;
            color: var(--dark-gray);
            cursor: pointer;
            font-size: 18px;
            transition: var(--transition);
        }

        .sidebar-header button:hover {
            color: var(--danger-color);
        }

        .sidebar-content {
            padding: 20px;
        }

        .toolbar {
            position: fixed;
            top: 70px;
            left: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .control-panel {
            background: var(--white);
            padding: 12px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            min-width: 250px;
        }

        .control-panel-header {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 10px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .control-panel-header i {
            margin-right: 8px;
        }

        .control-panel-body {
            margin-top: 10px;
        }

        select.form-control {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--medium-gray);
            border-radius: var(--border-radius);
            background-color: var(--white);
            font-size: 14px;
            color: var(--text-color);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23333333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn i {
            margin-right: 6px;
        }

        .mobile-toggle {
            position: fixed;
            top: 70px;
            right: 10px;
            z-index: 1000;
            background: var(--white);
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .mobile-toggle:hover {
            background-color: var(--light-gray);
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table td {
            padding: 10px 8px;
            border-bottom: 1px solid var(--medium-gray);
            font-size: 14px;
        }

        .details-table td:first-child {
            font-weight: 500;
            width: 40%;
            color: var(--text-light);
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background-color: rgba(16, 124, 16, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background-color: rgba(255, 140, 0, 0.1);
            color: var(--warning-color);
        }

        .badge-info {
            background-color: rgba(0, 120, 215, 0.1);
            color: var(--secondary-color);
        }

        .error-toast {
            position: fixed;
            top: 70px;
            right: 10px;
            background: var(--danger-color);
            color: var(--white);
            padding: 12px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .leaflet-popup-content-wrapper {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
        }

        .leaflet-popup-content {
            margin: 12px 16px;
            min-width: 200px;
        }

        .popup-content h3 {
            margin: 0 0 8px;
            font-size: 16px;
            color: var(--primary-color);
        }

        .popup-content p {
            margin: 0 0 10px;
            font-size: 14px;
            color: var(--text-light);
        }

        .popup-footer {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .debug-panel {
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: var(--white);
            padding: 10px;
            border-radius: var(--border-radius);
            font-family: monospace;
            font-size: 12px;
            z-index: 1000;
            max-width: 300px;
            max-height: 150px;
            overflow: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                right: -100%;
            }

            .control-panel {
                min-width: unset;
                width: calc(100vw - 20px);
            }

            .app-title {
                font-size: 18px;
            }
        }

        .section-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--primary-color);
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .document-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .document-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .document-item:last-child {
            border-bottom: none;
        }

        .document-icon {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .document-link {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .document-link:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .legend {
            position: fixed;
            bottom: 20px;
            right: 10px;
            background: var(--white);
            padding: 10px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            z-index: 1000;
            font-size: 12px;
        }

        .legend-title {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            border-radius: 3px;
        }
    </style>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
</head>

<body>
    <!-- App Header -->
    <header class="app-header">
        <div class="app-title"><i class="fas fa-map-marked-alt"></i> InvestUz - Investment Map</div>
    </header>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" class="loading">
        <div class="spinner"></div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="control-panel">
            <div class="control-panel-header">
                <span><i class="fas fa-layer-group"></i> District Filter</span>
            </div>
            <div class="control-panel-body">
                <select id="district-selector" class="form-control">
                    <option value="all">All Districts</option>
                    <option value="bektemir">Bektemir</option>
                    <option value="chilonzor">Chilonzor</option>
                    <option value="mirobod">Mirobod</option>
                    <option value="mirzo_ulugbek">Mirzo Ulugʻbek</option>
                    <option value="sergeli">Sergeli</option>
                    <option value="shayhontohur">Shayhontohur</option>
                    <option value="uchtepa">Uchtepa</option>
                    <option value="yangihayot">Yangihayot</option>
                    <option value="yashnobod">Yashnobod</option>
                    <option value="yunusabod">Yunusobod</option>
                </select>
            </div>
        </div>

        <div class="control-panel">
            <div class="control-panel-header">
                <span><i class="fas fa-filter"></i> Display Options</span>
            </div>
            <div class="control-panel-body">
                <label style="display: flex; align-items: center; margin-bottom: 8px;">
                    <input type="checkbox" id="show-markers" checked style="margin-right: 8px;">
                    <span>Show Markers</span>
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" id="show-polygons" checked style="margin-right: 8px;">
                    <span>Show Polygons</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Mobile Toggle -->
    <div id="mobile-toggle" class="mobile-toggle">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Legend -->
    <div class="legend">
        <div class="legend-title">Legend</div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: rgba(43, 87, 151, 0.5);"></div>
            <span>Investment Areas</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: rgba(16, 124, 16, 0.5);"></div>
            <span>Approved Projects</span>
        </div>
    </div>

    <!-- Debug Panel (only shown when DEBUG is true) -->
    <div id="debug-panel" class="debug-panel" style="display: none;">
        <div>Debug Information</div>
        <div id="debug-content"></div>
    </div>

    <!-- Application Script -->
    <script>
        // Create global namespace
        window.MapApp = {
            DEBUG: false,
            VERSION: '1.0.1',
            CURRENT_DATE: new Date().toISOString(),
            USER: 'InvestUz'
        };

        // Debug Logger
        const Logger = {
            log(type, ...args) {
                const timestamp = new Date().toISOString();
                console.log(`[${timestamp}] [${type}]`, ...args);

                if (window.MapApp.DEBUG) {
                    const debugPanel = document.getElementById('debug-panel');
                    const debugContent = document.getElementById('debug-content');

                    if (debugPanel && debugContent) {
                        debugPanel.style.display = 'block';
                        const message = document.createElement('div');
                        message.textContent = `[${type}] ${args.map(arg =>
                    typeof arg === 'object' ? JSON.stringify(arg) : arg).join(' ')}`;
                        debugContent.appendChild(message);

                        // Keep only the last 5 messages
                        while (debugContent.children.length > 5) {
                            debugContent.removeChild(debugContent.firstChild);
                        }
                    }
                }
            },
            debug(...args) {
                this.log('DEBUG', ...args);
            },
            info(...args) {
                this.log('INFO', ...args);
            },
            error(...args) {
                this.log('ERROR', ...args);
            }
        };

        // Utility Functions
        const Utils = {
            // Convert DMS (degrees, minutes, seconds) to decimal degrees
            dmsToDecimal(dmsStr) {
                try {
                    // Example: "41°19'7.54"С"
                    const regex = /(\d+)°(\d+)'(\d+\.\d+)"([СNЮSВEЗW])/;
                    const match = dmsStr.match(regex);

                    if (!match) {
                        throw new Error(`Invalid DMS format: ${dmsStr}`);
                    }

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
                } catch (error) {
                    Logger.error('Error converting DMS to decimal:', error);
                    return null;
                }
            },

            // Format status for display
            formatStatus(status) {
                switch (status) {
                    case "9":
                        return {
                            text: "Инвест договор", class: "badge-success"
                        };
                    case "1":
                        return {
                            text: "В разработке", class: "badge-warning"
                        };
                    default:
                        return {
                            text: "Статус: " + status, class: "badge-info"
                        };
                }
            },

            // Show loading indicator
            showLoading() {
                document.getElementById('loading-indicator').classList.add('active');
            },

            // Hide loading indicator
            hideLoading() {
                document.getElementById('loading-indicator').classList.remove('active');
            },

            // Show error toast
            showError(message, duration = 5000) {
                const toast = document.createElement('div');
                toast.className = 'error-toast';
                toast.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        `;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }
        };

        // Initialize Application
        (function(APP) {
            // Configuration
            APP.CONFIG = {
                MAP: {
                    CENTER: [41.311, 69.279],
                    DEFAULT_ZOOM: 12,
                    MIN_ZOOM: 10,
                    MAX_ZOOM: 18,
                    TILE_URL: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    ATTRIBUTION: '© OpenStreetMap contributors | InvestUz'
                },
                POLYGON: {
                    STYLE: {
                        color: '#2b5797',
                        weight: 2,
                        opacity: 0.7,
                        fillColor: '#2b5797',
                        fillOpacity: 0.2
                    },
                    HOVER_STYLE: {
                        weight: 3,
                        color: '#0078d7',
                        fillOpacity: 0.4
                    },
                    APPROVED_STYLE: {
                        color: '#107c10',
                        fillColor: '#107c10'
                    }
                },
                MARKER: {
                    ICON: L.icon({
                        iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png',
                        iconRetinaUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                        shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                },
                API: {
                    BASE_URL: '/api',
                    ENDPOINTS: {
                        LOTS: '/aktivs'
                    }
                },
                LAYERS: {
                    STREET: {
                        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                        attribution: '© OpenStreetMap contributors',
                        name: 'Street'
                    },
                    SATELLITE: {
                        url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                        attribution: '© Esri',
                        name: 'Satellite'
                    },
                    HYBRID: {
                        url: 'https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',
                        attribution: '© Google',
                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                        name: 'Hybrid'
                    }
                },

            };

            // Application state
            APP.state = {
                map: null,
                markerCluster: null,
                markers: [],
                polygons: {},
                currentDistrict: 'all',
                currentSidebar: null,
                filters: {
                    showMarkers: true,
                    showPolygons: true
                },
                mockApiData: {}
            };

            // Initialize map
            APP.initMap = function() {
                try {
                    Logger.info('Initializing map...');
                    Utils.showLoading();

                    // Create map instance
                    const map = L.map('map', {
                        center: APP.CONFIG.MAP.CENTER,
                        zoom: APP.CONFIG.MAP.DEFAULT_ZOOM,
                        minZoom: APP.CONFIG.MAP.MIN_ZOOM,
                        maxZoom: APP.CONFIG.MAP.MAX_ZOOM,
                        zoomControl: false
                    });

                    // Add zoom control
                    L.control.zoom({
                        position: 'bottomright'
                    }).addTo(map);

                    // Add tile layer
                    L.tileLayer(APP.CONFIG.MAP.TILE_URL, {
                        attribution: APP.CONFIG.MAP.ATTRIBUTION
                    }).addTo(map);

                    // Initialize marker cluster
                    const markerCluster = L.markerClusterGroup({
                        chunkedLoading: true,
                        maxClusterRadius: 50,
                        spiderfyOnMaxZoom: true,
                        showCoverageOnHover: false,
                        disableClusteringAtZoom: 16
                    });

                    map.addLayer(markerCluster);

                    // Update state references
                    APP.state.map = map;
                    APP.state.markerCluster = markerCluster;

                    Logger.info('Map initialized successfully');
                    Utils.hideLoading();
                    return true;
                } catch (error) {
                    Logger.error('Map initialization failed:', error);
                    Utils.hideLoading();
                    Utils.showError('Failed to initialize map. Please reload the page.');
                    return false;
                }
            };

            // Load lots data
            APP.loadData = async function() {
                try {
                    Logger.info('Loading lots data...');
                    Utils.showLoading();

                    // Fetch data from API
                    const response = await fetch(APP.CONFIG.API.BASE_URL + APP.CONFIG.API.ENDPOINTS.LOTS);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    if (!data.lots || !Array.isArray(data.lots)) {
                        throw new Error('Invalid data format - lots array missing');
                    }

                    // Process each lot
                    await Promise.all(data.lots.map(lot => {
                        try {
                            // Add marker if coordinates exist
                            if (lot.lat && lot.lng) {
                                APP.addMarker(lot);
                            }

                            // Add polygon if polygon data exists
                            if (lot.polygons && lot.polygons.length > 0) {
                                APP.addPolygon(lot);
                            }
                        } catch (error) {
                            Logger.error('Failed to process lot:', lot.id, error);
                        }
                    }));

                    Logger.info('Data loaded successfully:', data.lots.length, 'lots');
                    Utils.hideLoading();
                } catch (error) {
                    Logger.error('Failed to load data:', error);
                    Utils.hideLoading();
                    Utils.showError('Failed to load investment data. Please try again later.');

                    // Optionally, you can fall back to mock data in development mode
                    if (APP.DEBUG) {
                        Logger.info('Falling back to mock data in debug mode...');
                        return APP.loadMockData();
                    }
                }
            };

            APP.loadMockData = async function() {
                try {
                    const data = APP.state.mockApiData;

                    if (!data.lots || !Array.isArray(data.lots)) {
                        throw new Error('Invalid mock data format - lots array missing');
                    }

                    // Process mock data
                    await Promise.all(data.lots.map(lot => {
                        try {
                            if (lot.lat && lot.lng) {
                                APP.addMarker(lot);
                            }
                            if (lot.polygons && lot.polygons.length > 0) {
                                APP.addPolygon(lot);
                            }
                        } catch (error) {
                            Logger.error('Failed to process mock lot:', lot.id, error);
                        }
                    }));

                    Logger.info('Mock data loaded successfully:', data.lots.length, 'lots');
                    Utils.hideLoading();
                } catch (error) {
                    Logger.error('Failed to load mock data:', error);
                    Utils.hideLoading();
                    Utils.showError('Failed to load data');
                }
            };

            // Add marker to map
            APP.addMarker = function(lot) {
                try {
                    const marker = L.marker([lot.lat, lot.lng], {
                        icon: APP.CONFIG.MARKER.ICON
                    });

                    marker.bindPopup(APP.createPopupContent(lot));
                    APP.state.markerCluster.addLayer(marker);

                    // Store reference to marker and data
                    APP.state.markers.push({
                        marker,
                        data: lot
                    });

                    Logger.debug('Added marker for lot:', lot.id);
                    return marker;
                } catch (error) {
                    Logger.error('Failed to add marker for lot:', lot.id, error);
                    return null;
                }
            };

            // Add polygon to map
            APP.addPolygon = function(lot) {
                try {
                    // Extract coordinates from polygon data
                    const polygonCoords = APP.extractPolygonCoordinates(lot.polygons);

                    if (!polygonCoords || polygonCoords.length < 3) {
                        Logger.warn('Invalid polygon coordinates for lot:', lot.id);
                        return null;
                    }

                    // Determine polygon style based on lot status
                    let style = {
                        ...APP.CONFIG.POLYGON.STYLE
                    };
                    if (lot.status === "9") { // Approved status
                        style = {
                            ...style,
                            ...APP.CONFIG.POLYGON.APPROVED_STYLE
                        };
                    }

                    // Create polygon
                    const polygon = L.polygon(polygonCoords, style);

                    // Add event listeners
                    polygon.on('mouseover', function() {
                        polygon.setStyle(APP.CONFIG.POLYGON.HOVER_STYLE);
                    });

                    polygon.on('mouseout', function() {
                        polygon.setStyle(style);
                    });

                    polygon.on('click', function() {
                        APP.showDetails(lot.id);
                    });

                    // Add to map
                    polygon.addTo(APP.state.map);

                    // Store reference
                    APP.state.polygons[lot.id] = {
                        polygon,
                        data: lot
                    };

                    Logger.debug('Added polygon for lot:', lot.id);
                    return polygon;
                } catch (error) {
                    Logger.error('Failed to add polygon for lot:', lot.id, error);
                    return null;
                }
            };

            // Extract polygon coordinates from DMS format
            APP.extractPolygonCoordinates = function(polygonData) {
                try {
                    if (!polygonData || !Array.isArray(polygonData) || polygonData.length < 3) {
                        return null;
                    }

                    // Convert DMS coordinates to decimal
                    const coordinates = polygonData.map(point => {
                        const lat = Utils.dmsToDecimal(point.start_lat);
                        const lng = Utils.dmsToDecimal(point.start_lon);

                        if (lat === null || lng === null) {
                            throw new Error(
                                `Invalid coordinates: ${point.start_lat}, ${point.start_lon}`);
                        }

                        return [lat, lng];
                    });

                    return coordinates;
                } catch (error) {
                    Logger.error('Failed to extract polygon coordinates:', error);
                    return null;
                }
            };

            // Create popup content
            APP.createPopupContent = function(lot) {
                const statusInfo = Utils.formatStatus(lot.status);

                return `
            <div class="popup-content">
                <h3>${lot.neighborhood_name || 'Unnamed Location'}</h3>
                <p>${lot.district_name || ''}</p>
                <p>
                    ${lot.area_hectare ? `<strong>Площадь:</strong> ${lot.area_hectare} га` : ''}
                    ${statusInfo ? `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>` : ''}
                </p>
                <div class="popup-footer">
                    <button class="btn btn-primary btn-sm" onclick="MapApp.showDetails('${lot.id}')">
                        <i class="fas fa-info-circle"></i> Подробнее
                    </button>
                </div>
            </div>
        `;
            };

            // Show lot details in sidebar
            APP.showDetails = function(lotId) {
                try {
                    Logger.info('Showing details for lot:', lotId);

                    // Find lot data
                    const markerData = APP.state.markers.find(m => m.data.id === lotId);
                    const polygonData = APP.state.polygons[lotId];
                    const lot = (markerData ? markerData.data : (polygonData ? polygonData.data : null));

                    if (!lot) {
                        throw new Error(`Lot with ID ${lotId} not found`);
                    }

                    // Close existing sidebar if any
                    APP.closeSidebar();

                    // Get status formatting
                    const statusInfo = Utils.formatStatus(lot.status);

                    // Create sidebar element
                    const sidebar = document.createElement('div');
                    sidebar.className = 'sidebar';
                    sidebar.innerHTML = `
                <div class="sidebar-header">
                    <h2>${lot.neighborhood_name || 'Unnamed Location'}</h2>
                    <button onclick="MapApp.closeSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="sidebar-content">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i> Основная информация
                    </div>
                    <table class="details-table">
                        <tr>
                            <td>Район:</td>
                            <td>${lot.district_name || 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Площадь:</td>
                            <td>${lot.area_hectare ? lot.area_hectare + ' га' : 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Статус:</td>
                            <td><span class="badge ${statusInfo.class}">${statusInfo.text}</span></td>
                        </tr>
                        <tr>
                            <td>Стратегия:</td>
                            <td>${lot.area_strategy || 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Номер решения:</td>
                            <td>${lot.decision_number || 'Н/Д'}</td>
                        </tr>
                    </table>

                    <div class="section-title">
                        <i class="fas fa-building"></i> Технические параметры
                    </div>
                    <table class="details-table">
                        <tr>
                            <td>Кадастр:</td>
                            <td>${lot.cadastre_certificate || 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Этажность:</td>
                            <td>${lot.proposed_floors || 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Коэффициент КМН:</td>
                            <td>${lot.qmn_percentage || 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Коэффициент УМН:</td>
                            <td>${lot.umn_coefficient || 'Н/Д'}</td>
                        </tr>
                    </table>

                    <div class="section-title">
                        <i class="fas fa-expand-arrows-alt"></i> Площади
                    </div>
                    <table class="details-table">
                        <tr>
                            <td>Жилая площадь:</td>
                            <td>${lot.residential_area ? lot.residential_area + ' м²' : 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Нежилая площадь:</td>
                            <td>${lot.non_residential_area ? lot.non_residential_area + ' м²' : 'Н/Д'}</td>
                        </tr>
                        <tr>
                            <td>Общая площадь:</td>
                            <td>${lot.total_building_area ? lot.total_building_area + ' м²' : 'Н/Д'}</td>
                        </tr>
                    </table>

                    ${lot.documents && lot.documents.length > 0 ? `
                                                <div class="section-title">
                                                    <i class="fas fa-file-alt"></i> Документы
                                                </div>
                                                <ul class="document-list">
                                                    ${lot.documents.map(doc => `
                                <li class="document-item">
                                    <span class="document-icon"><i class="fas fa-file-pdf"></i></span>
                                    <a href="${doc.url}" target="_blank" class="document-link">
                                        ${doc.filename}
                                    </a>
                                </li>
                            `).join('')}
                                                </ul>
                                            ` : ''}
                </div>
            `;

                    // Append to body and open
                    document.body.appendChild(sidebar);
                    APP.state.currentSidebar = sidebar;

                    // Use a small timeout to trigger CSS transition
                    setTimeout(() => sidebar.classList.add('open'), 10);

                    // If we have polygon data, zoom to it
                    if (polygonData && polygonData.polygon) {
                        APP.state.map.fitBounds(polygonData.polygon.getBounds(), {
                            padding: [50, 50],
                            maxZoom: 17
                        });
                    }
                    // Otherwise, if we have marker data, zoom to it
                    else if (markerData && markerData.marker) {
                        APP.state.map.setView(markerData.marker.getLatLng(), 17);
                    }

                } catch (error) {
                    Logger.error('Failed to show details:', error);
                    Utils.showError('Failed to load investment details');
                }
            };

            // Close sidebar
            APP.closeSidebar = function() {
                if (APP.state.currentSidebar) {
                    APP.state.currentSidebar.classList.remove('open');
                    setTimeout(() => {
                        APP.state.currentSidebar.remove();
                        APP.state.currentSidebar = null;
                    }, 300);
                }
            };

            // Filter markers and polygons by district
            APP.filterByDistrict = function(districtName) {
                try {
                    Logger.info('Filtering by district:', districtName);
                    APP.state.currentDistrict = districtName;

                    // Clear existing markers and add filtered ones
                    APP.state.markerCluster.clearLayers();

                    // Add markers that match the filter or all if 'all' is selected
                    APP.state.markers.forEach(({
                        marker,
                        data
                    }) => {
                        const shouldShow =
                            districtName === 'all' ||
                            (data.district_name && data.district_name.toLowerCase().includes(
                                districtName.toLowerCase()));

                        if (shouldShow && APP.state.filters.showMarkers) {
                            APP.state.markerCluster.addLayer(marker);
                        }
                    });

                    // Handle polygons visibility
                    Object.values(APP.state.polygons).forEach(({
                        polygon,
                        data
                    }) => {
                        const shouldShow =
                            districtName === 'all' ||
                            (data.district_name && data.district_name.toLowerCase().includes(
                                districtName.toLowerCase()));

                        if (shouldShow && APP.state.filters.showPolygons) {
                            if (!APP.state.map.hasLayer(polygon)) {
                                polygon.addTo(APP.state.map);
                            }
                        } else {
                            if (APP.state.map.hasLayer(polygon)) {
                                APP.state.map.removeLayer(polygon);
                            }
                        }
                    });

                    Logger.debug('Filter applied:', districtName);
                } catch (error) {
                    Logger.error('Failed to apply filter:', error);
                    Utils.showError('Failed to apply filter');
                }
            };

            // Toggle markers visibility
            APP.toggleMarkers = function(show) {
                try {
                    APP.state.filters.showMarkers = show;

                    if (show) {
                        // Re-apply district filter to show only relevant markers
                        APP.filterByDistrict(APP.state.currentDistrict);
                    } else {
                        // Clear all markers
                        APP.state.markerCluster.clearLayers();
                    }

                    Logger.debug('Markers visibility toggled:', show);
                } catch (error) {
                    Logger.error('Failed to toggle markers:', error);
                }
            };

            // Toggle polygons visibility
            APP.togglePolygons = function(show) {
                try {
                    APP.state.filters.showPolygons = show;

                    Object.values(APP.state.polygons).forEach(({
                        polygon,
                        data
                    }) => {
                        const matchesDistrict =
                            APP.state.currentDistrict === 'all' ||
                            (data.district_name && data.district_name.toLowerCase().includes(APP.state
                                .currentDistrict.toLowerCase()));

                        if (show && matchesDistrict) {
                            if (!APP.state.map.hasLayer(polygon)) {
                                polygon.addTo(APP.state.map);
                            }
                        } else {
                            if (APP.state.map.hasLayer(polygon)) {
                                APP.state.map.removeLayer(polygon);
                            }
                        }
                    });

                    Logger.debug('Polygons visibility toggled:', show);
                } catch (error) {
                    Logger.error('Failed to toggle polygons:', error);
                }
            };

            // Generate mock data for testing
            APP.generateMockData = function() {
                if (!APP.DEBUG) return;

                Logger.info('Generating additional mock data for testing...');

                // Define districts of Tashkent
                const districts = [
                    'Bektemir', 'Chilonzor', 'Mirobod', 'Mirzo Ulugʻbek',
                    'Sergeli', 'Shayhontohur', 'Uchtepa', 'Yangihayot',
                    'Yashnobod', 'Yunusobod'
                ];

                // Base coordinates for Tashkent
                const baseLatCenter = 41.311;
                const baseLngCenter = 69.279;

                // Generate 20 additional random lots
                for (let i = 0; i < 20; i++) {
                    const id = (100 + i).toString();
                    const district = districts[Math.floor(Math.random() * districts.length)];

                    // Random coordinates within Tashkent area
                    const latOffset = (Math.random() - 0.5) * 0.1;
                    const lngOffset = (Math.random() - 0.5) * 0.1;
                    const lat = baseLatCenter + latOffset;
                    const lng = baseLngCenter + lngOffset;

                    // Random area
                    const area = (Math.random() * 5 + 0.1).toFixed(2);

                    // Random status
                    const status = Math.random() > 0.5 ? "9" : "1";

                    const lot = {
                        id: id,
                        neighborhood_name: `Участок ${id} (${area} га)`,
                        area_hectare: parseFloat(area),
                        status: status,
                        district_name: district,
                        lat: lat,
                        lng: lng,
                        decision_number: Math.floor(Math.random() * 100).toString(),
                        area_strategy: "заключение инвест договора",
                        cadastre_certificate: `Сертификат ${Math.floor(Math.random() * 1000)}`,
                        proposed_floors: Math.floor(Math.random() * 20 + 1).toString(),
                        qmn_percentage: Math.floor(Math.random() * 20).toString(),
                        umn_coefficient: Math.floor(Math.random() * 10).toString(),
                        adjacent_area: Math.floor(Math.random() * 2000),
                        residential_area: Math.floor(Math.random() * 1000),
                        non_residential_area: Math.floor(Math.random() * 500),
                        total_building_area: Math.floor(Math.random() * 1500),
                        documents: []
                    };

                    // Add to mock data
                    APP.state.mockApiData.lots.push(lot);
                }

                Logger.info('Mock data generated:', APP.state.mockApiData.lots.length, 'total lots');
            };

            // Setup event listeners
            APP.setupEventListeners = function() {
                try {
                    Logger.info('Setting up event listeners...');

                    // District selector change event
                    const districtSelector = document.getElementById('district-selector');
                    if (districtSelector) {
                        districtSelector.addEventListener('change', function() {
                            const selectedDistrict = this.value;
                            APP.filterByDistrict(selectedDistrict);
                        });
                    }

                    // Show markers checkbox
                    const showMarkersCheckbox = document.getElementById('show-markers');
                    if (showMarkersCheckbox) {
                        showMarkersCheckbox.addEventListener('change', function() {
                            APP.toggleMarkers(this.checked);
                        });
                    }

                    // Show polygons checkbox
                    const showPolygonsCheckbox = document.getElementById('show-polygons');
                    if (showPolygonsCheckbox) {
                        showPolygonsCheckbox.addEventListener('change', function() {
                            APP.togglePolygons(this.checked);
                        });
                    }

                    // Mobile menu toggle
                    const mobileToggle = document.getElementById('mobile-toggle');
                    if (mobileToggle) {
                        mobileToggle.addEventListener('click', function() {
                            const toolbar = document.querySelector('.toolbar');
                            if (toolbar) {
                                toolbar.classList.toggle('show-mobile');
                            }
                        });
                    }

                    // Map click event to close sidebar on mobile
                    APP.state.map.on('click', function() {
                        if (window.innerWidth <= 768) {
                            APP.closeSidebar();
                        }
                    });

                    // Window resize event
                    window.addEventListener('resize', function() {
                        APP.state.map.invalidateSize();
                    });

                    Logger.info('Event listeners setup complete');
                } catch (error) {
                    Logger.error('Failed to setup event listeners:', error);
                }
            };

            // Initialize application
            APP.init = async function() {
                try {
                    Utils.showLoading();

                    // Enable debug panel if in debug mode
                    if (APP.DEBUG) {
                        document.getElementById('debug-panel').style.display = 'block';
                    }

                    // Initialize map
                    if (!APP.initMap()) {
                        throw new Error('Map initialization failed');
                    }

                    // Generate mock data if in debug mode
                    APP.generateMockData();

                    // Load data
                    await APP.loadData();

                    // Setup event listeners
                    APP.setupEventListeners();

                    // Close loading indicator
                    Utils.hideLoading();

                    Logger.info('Application initialized successfully');
                } catch (error) {
                    Logger.error('Application initialization failed:', error);
                    Utils.hideLoading();
                    Utils.showError('Failed to initialize application. Please reload the page.');
                }
            };

            // Initialize on DOM ready
            document.addEventListener('DOMContentLoaded', function() {
                APP.init();
            });
        })(window.MapApp = window.MapApp || {});
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/1.13.0/Control.Geocoder.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/1.13.0/Control.Geocoder.js.map"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.js.map"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.min.js.map"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.min.js.map"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-zoomhome/1.0.0/leaflet.zoomhome.min.js.map"></script>
