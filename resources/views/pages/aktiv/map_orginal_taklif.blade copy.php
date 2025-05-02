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

    <!-- Application Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.5;
        }

        #map {
            width: 100%;
            height: 100vh;
            background: #f8f9fa;
            z-index: 1;
        }

        .loading {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(37,117,252,0.2);
            border-top-color: #2575fc;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-content {
            padding: 20px;
        }

        .district-selector {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .mobile-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                right: -100%;
            }
            .mobile-toggle {
                display: block;
            }
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .details-table td:first-child {
            font-weight: 500;
            width: 40%;
            color: #666;
        }

        .error-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff5252;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .debug-panel {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            z-index: 1000;
        }
    </style>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
</head>
<body>
    <!-- Map Container -->
    <div id="map"></div>

    <!-- Loading Indicator -->
    <div class="loading">
        <div class="spinner"></div>
    </div>

    <!-- District Selector -->
    <div class="district-selector">
        <select id="district-selector">
            <option value="tashkent">Toshkent shahri</option>
            <option value="bektemir">Bektemir tumani</option>
            <option value="chilonzor">Chilonzor tumani</option>
            <option value="mirobod">Mirobod tumani</option>
            <option value="mirzo_ulugbek">Mirzo Ulugʻbek tumani</option>
            <option value="sergeli">Sergeli tumani</option>
            <option value="shayhontohur">Shayhontohur tumani</option>
            <option value="uchtepa">Uchtepa tumani</option>
            <option value="yangihayot">Yangihayot tumani</option>
            <option value="yashnobod">Yashnobod tumani</option>
            <option value="yunusabod">Yunusobod tumani</option>
        </select>
    </div>

    <!-- Mobile Toggle -->
    <button id="mobile-toggle" class="mobile-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Application Script -->
    <script>
        // Create global namespace
        window.MapApp = {
            DEBUG: true,
            VERSION: '1.0.0',
            CURRENT_DATE: '2025-05-02 13:13:14',
            USER: 'InvestUz'
        };

        // Debug Logger
        const Logger = {
            log(type, ...args) {
                const timestamp = new Date().toISOString();
                console.log(`[${timestamp}] [${type}]`, ...args);
            },
            debug(...args) { this.log('DEBUG', ...args); },
            info(...args) { this.log('INFO', ...args); },
            error(...args) { this.log('ERROR', ...args); }
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
                    // Using OpenStreetMap tiles
                    TILE_URL: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    ATTRIBUTION: '© OpenStreetMap contributors'
                }
            };

            // Initialize map
            APP.initMap = function() {
                try {
                    Logger.info('Initializing map...');

                    // Create map instance
                    const map = L.map('map', {
                        center: APP.CONFIG.MAP.CENTER,
                        zoom: APP.CONFIG.MAP.DEFAULT_ZOOM,
                        minZoom: APP.CONFIG.MAP.MIN_ZOOM,
                        maxZoom: APP.CONFIG.MAP.MAX_ZOOM,
                        zoomControl: false
                    });

                    // Add zoom control
                    L.control.zoom({ position: 'topright' }).addTo(map);

                    // Add tile layer
                    L.tileLayer(APP.CONFIG.MAP.TILE_URL, {
                        attribution: APP.CONFIG.MAP.ATTRIBUTION
                    }).addTo(map);

                    // Initialize marker cluster
                    const markerCluster = L.markerClusterGroup({
                        chunkedLoading: true,
                        maxClusterRadius: 50,
                        spiderfyOnMaxZoom: true,
                        showCoverageOnHover: false
                    });

                    map.addLayer(markerCluster);

                    // Store references
                    APP.state = {
                        map,
                        markerCluster,
                        markers: [],
                        polygons: {}
                    };

                    Logger.info('Map initialized successfully');
                    return true;
                } catch (error) {
                    Logger.error('Map initialization failed:', error);
                    return false;
                }
            };

            // Load markers
            APP.loadMarkers = async function() {
                try {
                    const response = await fetch('/api/aktivs');
                    const data = await response.json();

                    if (!data.lots) {
                        throw new Error('No marker data received');
                    }

                    data.lots.forEach(lot => {
                        try {
                            if (lot.lat && lot.lng) {
                                const marker = L.marker([lot.lat, lot.lng]);
                                marker.bindPopup(createPopupContent(lot));
                                APP.state.markerCluster.addLayer(marker);
                                APP.state.markers.push({ marker, data: lot });
                            }
                        } catch (error) {
                            Logger.error('Failed to process lot:', lot, error);
                        }
                    });

                    Logger.info('Markers loaded:', data.lots.length);
                } catch (error) {
                    Logger.error('Failed to load markers:', error);
                    showError('Failed to load markers');
                }
            };

            // Helper Functions
            function createPopupContent(lot) {
                return `
                    <div class="popup-content">
                        <h3>${lot.neighborhood_name || 'Unnamed Location'}</h3>
                        <p>${lot.area_hectare ? lot.area_hectare + ' га' : ''}</p>
                        <button onclick="MapApp.showDetails('${lot.id}')">
                            Подробнее
                        </button>
                    </div>
                `;
            }

            function showError(message) {
                const toast = document.createElement('div');
                toast.className = 'error-toast';
                toast.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i>
                    <span>${message}</span>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            }

            // Event Handlers
            APP.showDetails = function(lotId) {
                const lot = APP.state.markers.find(m => m.data.id === lotId)?.data;
                if (!lot) return;

                const sidebar = document.createElement('div');
                sidebar.className = 'sidebar';
                sidebar.innerHTML = `
                    <div class="sidebar-header">
                        <h2>${lot.neighborhood_name || 'Unnamed Location'}</h2>
                        <button onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="sidebar-content">
                        <table class="details-table">
                            <tr>
                                <td>Площадь:</td>
                                <td>${lot.area_hectare ? lot.area_hectare + ' га' : 'Н/Д'}</td>
                            </tr>
                            <tr>
                                <td>Статус:</td>
                                <td>${lot.status || 'Н/Д'}</td>
                            </tr>
                        </table>
                    </div>
                `;

                document.body.appendChild(sidebar);
                setTimeout(() => sidebar.classList.add('open'), 10);
            };

            // Initialize on DOM ready
            document.addEventListener('DOMContentLoaded', async function() {
                if (APP.initMap()) {
                    await APP.loadMarkers();
                }
            });

        })(window.MapApp);
    </script>
</body>
</html>
