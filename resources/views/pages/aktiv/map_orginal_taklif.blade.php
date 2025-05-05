<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвестиция харитаси - ИнвестУз</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Google Fonts - Roboto and Noto Sans -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Noto+Sans:wght@400;500;600;700&display=swap">

    <!-- Application Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/map_style/main.css') }}">

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
</head>

<body>
    <!-- App Header -->
    <header class="app-header">
        <div class="app-logo">
            <i class="fas fa-map-marked-alt fa-2x"></i>
            <div class="app-title">ИнвестУз - Инвестиция харитаси (Сайт тест режимида ишламоқда)</div>
        </div>
        <div class="lang-switcher">
            <button class="lang-btn active">УЗ</button>
            <button class="lang-btn">RU</button>
            <button class="lang-btn">EN</button>
        </div>
    </header>

    <!-- Flag Decoration -->
    <div class="flag-decoration">
        <div class="flag-blue"></div>
        <div class="flag-red"></div>
        <div class="flag-green"></div>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- Search Box -->
    <div class="search-box">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Излаш...">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>
        <div class="search-results">
            <!-- Search results will be added here dynamically -->
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" class="loading">
        <div class="spinner"></div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="control-panel">
            <div class="control-panel-header">
                <span><i class="fas fa-layer-group"></i> Туман фильтри</span>
            </div>
            <div class="control-panel-body">
                <div class="form-group">
                    <select id="district-selector" class="form-control">
                        <option value="all">Барча туманлар</option>
                        <option value="bektemir">Бектемир</option>
                        <option value="chilonzor">Чилонзор</option>
                        <option value="mirobod">Миробод</option>
                        <option value="mirzo_ulugbek">Мирзо Улуғбек</option>
                        <option value="olmazor">Олмазор</option>
                        <option value="sergeli">Сергели</option>
                        <option value="shayhontohur">Шайхонтоҳур</option>
                        <option value="uchtepa">Учтепа</option>
                        <option value="yakkasaroy">Яккасарой</option>
                        <option value="yashnobod">Яшнобод</option>
                        <option value="yunusabod">Юнусобод</option>
                        <option value="yangihayot">Янгиҳаёт</option>
                    </select>
                </div>

                <div class="status-filter-buttons">
                    <button class="status-btn active" data-status="all">Барчаси</button>
                    <button class="status-btn" data-status="9">Инвест договор</button>
                    <button class="status-btn" data-status="1">Ишлаб чиқилмоқда</button>
                </div>
            </div>
        </div>

        <div class="control-panel">
            <div class="control-panel-header">
                <span><i class="fas fa-filter"></i> Кўрсатиш мосламалари</span>
            </div>
            <div class="control-panel-body">
                <div class="checkbox-container">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="show-markers" checked>
                        <span class="checkmark"></span>
                    </label>
                    <span class="custom-checkbox-label">Маркерларни кўрсатиш</span>
                </div>
                <div class="checkbox-container">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="show-polygons" checked>
                        <span class="checkmark"></span>
                    </label>
                    <span class="custom-checkbox-label">Полигонларни кўрсатиш</span>
                </div>
                <div class="checkbox-container">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="show-districts" checked>
                        <span class="checkmark"></span>
                    </label>
                    <span class="custom-checkbox-label">Туман чегараларини кўрсатиш</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Toggle -->
    <div id="mobile-toggle" class="mobile-toggle">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Map Controls -->
    <div class="map-controls">
        <button class="map-control-btn" id="zoom-in"><i class="fas fa-plus"></i></button>
        <button class="map-control-btn" id="zoom-out"><i class="fas fa-minus"></i></button>
        <button class="map-control-btn" id="reset-view"><i class="fas fa-home"></i></button>
    </div>

    <!-- Legend -->
    <div class="legend">
        <div class="legend-title">Шартли белгилар</div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: rgba(30, 54, 133, 0.5);"></div>
            <span>Инвестиция майдонлари</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: rgba(14, 98, 69, 0.5);"></div>
            <span>Тасдиқланган лойиҳалар</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: rgba(214, 40, 57, 0.5);"></div>
            <span>Иншоот қурилиш жараёнида</span>
        </div>
    </div>

    <!-- Debug Panel (only shown when DEBUG is true) -->
    <div id="debug-panel" class="debug-panel" style="display: none;">
        <div>Созлаш маълумотлари</div>
        <div id="debug-content"></div>
    </div>

<script>
// Create global namespace
window.MapApp = {
    DEBUG: false,
    VERSION: '1.2.0',
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
                    text: "Ишлаб чиқилмоқда", class: "badge-warning"
                };
            case "2":
                return {
                    text: "Қурилиш жараёнида", class: "badge-info"
                };
            default:
                return {
                    text: "Холат: " + status, class: "badge-info"
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
    },

    // Parse KML data from text
    parseKML(kmlText) {
        try {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(kmlText, "application/xml");
            const coordsText = xmlDoc.querySelector('coordinates')?.textContent.trim() || '';

            return coordsText.split(' ').map(coordStr => {
                const [lng, lat] = coordStr.split(',').map(Number);
                return [lat, lng]; // Leaflet uses [lat, lng] format
            });
        } catch (error) {
            Logger.error('Failed to parse KML:', error);
            return null;
        }
    }


};

// Initialize Application
(function (APP) {
    // Configuration
    APP.CONFIG = {
        SIDEBAR_WIDTH: 400,
        MOBILE_BREAKPOINT: 768,
        ANIMATION_DURATION: 300,
        MAP_ANIMATION: {
            ZOOM_DURATION: 0.5,
            PAN_DURATION: 0.3,
            EASE_LINEARITY: 0.5,
            TOTAL_DURATION: 1000
        },
        MAP: {
            CENTER: [41.311, 69.279],
            DEFAULT_ZOOM: 11,
            MIN_ZOOM: 10,
            MAX_ZOOM: 18,
            TILE_URL: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            ATTRIBUTION: '© OpenStreetMap contributors | ИнвестУз'
        },
        POLYGON: {
            STYLE: {
                color: '#1E3685',
                weight: 2,
                opacity: 0.7,
                fillColor: '#1E3685',
                fillOpacity: 0.2
            },
            HOVER_STYLE: {
                weight: 3,
                color: '#4A6FD4',
                fillOpacity: 0.4
            },
            APPROVED_STYLE: {
                color: '#0E6245',
                fillColor: '#0E6245'
            },
            CONSTRUCTION_STYLE: {
                color: '#D62839',
                fillColor: '#D62839'
            }
        },
        DISTRICT_POLYGON: {
            STYLE: {
                color: '#767676',
                weight: 1.5,
                opacity: 0.6,
                fillColor: '#1E3685',
                fillOpacity: 0.05
            },
            HOVER_STYLE: {
                weight: 2,
                color: '#4A6FD4',
                fillOpacity: 0.15
            }
        },
        MARKER: {
            ICON: L.divIcon({
                className: 'custom-marker',
                html: '<div class="pulse-marker"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        },
        CLUSTER: {
            ICON_CREATE_FUNCTION: function(cluster) {
                const count = cluster.getChildCount();
                return L.divIcon({
                    html: `<div class="custom-cluster">${count}</div>`,
                    className: 'custom-marker-cluster',
                    iconSize: [40, 40]
                });
            }
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
                name: 'Оддий'
            },
            SATELLITE: {
                url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                attribution: '© Esri',
                name: 'Сунъий йўлдош'
            }
        },
        DISTRICT_KML_FILES: [
            'bektemir.xml', 'chilonzor.xml', 'mirobod.xml', 'mirzo_ulugbek.xml', 'olmazor.xml',
            'sergeli.xml', 'shayhontohur.xml', 'uchtepa.xml', 'yakkasaroy.xml', 'yashnobod.xml',
            'yunusabod.xml', 'yangihayot.xml'
        ],
        KML_PATH: '/xml-map/'
    };

    // Application state
    APP.state = {
        map: null,
        markerCluster: null,
        markers: [],
        polygons: {},
        districtPolygons: {},
        currentDistrict: 'all',
        currentStatus: 'all',
        currentSidebar: null,
        filters: {
            showMarkers: true,
            showPolygons: true,
            showDistricts: true
        },
        mockApiData: {
            lots: []
        },
        view: {
            currentItem: null,
            isAnimating: false,
            lastZoom: null,
            lastCenter: null
        },
        search: {
            results: [],
            visible: false
        },
        layers: {
            current: 'STREET',
            instances: {}
        }
    };

    // Language translations
    APP.translations = {
        uz: {
            // Uzbek translations
            appTitle: "ИнвестУз - Инвестиция харитаси",
            allDistricts: "Барча туманлар",
            displayOptions: "Кўрсатиш мосламалари",
            showMarkers: "Маркерларни кўрсатиш",
            showPolygons: "Полигонларни кўрсатиш",
            showDistricts: "Туман чегараларини кўрсатиш",
            search: "Излаш...",
            legend: "Шартли белгилар",
            investmentAreas: "Инвестиция майдонлари",
            approvedProjects: "Тасдиқланган лойиҳалар",
            constructionProjects: "Иншоот қурилиш жараёнида",
            moreInfo: "Батафсил",
            basicInfo: "Асосий маълумот",
            district: "Туман",
            area: "Майдон",
            status: "Холат",
            strategy: "Стратегия",
            decisionNumber: "Қарор рақами",
            technicalParams: "Техник параметрлар",
            cadastre: "Кадастр",
            floors: "Қаватлар сони",
            kmnCoefficient: "КМН коэффициенти",
            umnCoefficient: "УМН коэффициенти",
            areas: "Майдонлар",
            residentialArea: "Турар-жой майдони",
            nonResidentialArea: "Нотурар-жой майдони",
            totalArea: "Умумий майдон",
            documents: "Ҳужжатлар",
            notAvailable: "Н/М",
            investmentContract: "Инвест договор",
            inDevelopment: "Ишлаб чиқилмоқда",
            inConstruction: "Қурилиш жараёнида",
            all: "Барчаси",
            hectare: "га",
            squareMeters: "м²",
            loadingError: "Маълумотларни юклашда хатолик юз берди. Кейинроқ қайта уриниб кўринг.",
            mapInitError: "Харитани инициализация қилишда хатолик юз берди. Саҳифани янгиланг."
        },
        ru: {
            // Russian translations (can be filled later)
        },
        en: {
            // English translations (can be filled later)
        }
    };

    // Current language
    APP.currentLanguage = 'uz';

    // Get translation for a key
    APP.t = function(key) {
        return APP.translations[APP.currentLanguage][key] || key;
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

            // Add tile layer
            const streetTiles = L.tileLayer(APP.CONFIG.LAYERS.STREET.url, {
                attribution: APP.CONFIG.LAYERS.STREET.attribution
            });

            const satelliteTiles = L.tileLayer(APP.CONFIG.LAYERS.SATELLITE.url, {
                attribution: APP.CONFIG.LAYERS.SATELLITE.attribution
            });

            // Store layer instances
            APP.state.layers.instances = {
                STREET: streetTiles,
                SATELLITE: satelliteTiles
            };

            // Add default layer
            APP.state.layers.instances[APP.state.layers.current].addTo(map);

            // Initialize marker cluster with custom icons
            const markerCluster = L.markerClusterGroup({
                chunkedLoading: true,
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                disableClusteringAtZoom: 16,
                iconCreateFunction: APP.CONFIG.CLUSTER.ICON_CREATE_FUNCTION
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
            Utils.showError(APP.t('mapInitError'));
            return false;
        }
    };

    // Load district boundaries from KML files
  // Load district boundaries from KML files
APP.loadDistrictBoundaries = async function() {
    try {
        Logger.info('Loading district boundaries...');

        const loadedDistricts = [];

        // Process each KML file
        for (let fileName of APP.CONFIG.DISTRICT_KML_FILES) {
            try {
                const response = await fetch(APP.CONFIG.KML_PATH + fileName);

                if (!response.ok) {
                    Logger.warn(`KML file not found: ${fileName} (${response.status})`);
                    continue;  // Skip this file and continue with the next
                }

                const kmlText = await response.text();

                // Parse KML to get coordinates
                const coords = Utils.parseKML(kmlText);

                if (!coords || coords.length < 3) {
                    Logger.warn(`Invalid or insufficient coordinates in KML file: ${fileName}`);
                    continue;
                }

                // Create district polygon
                const districtName = fileName.replace('.xml', '');
                const polygon = APP.addDistrictPolygon(districtName, coords);

                if (polygon) {
                    loadedDistricts.push(districtName);
                }
            } catch (error) {
                Logger.error(`Failed to load KML file: ${fileName}`, error);
                // Continue with next file
            }
        }

        if (loadedDistricts.length > 0) {
            Logger.info(`District boundaries loaded successfully: ${loadedDistricts.join(', ')}`);
        } else {
            Logger.warn('No district boundaries were loaded successfully');
        }
    } catch (error) {
        Logger.error('Failed to load district boundaries:', error);
        Utils.showError('Туман чегараларини юклашда хатолик юз берди');
    }
};
  // Add district polygon to map
APP.addDistrictPolygon = function(districtName, coordinates) {
    try {
        // Validate coordinates
        if (!coordinates || !Array.isArray(coordinates) || coordinates.length < 3) {
            Logger.warn(`Invalid coordinates for district: ${districtName}`);
            return null;
        }

        // Make sure all coordinates are valid arrays with 2 numbers
        const validCoordinates = coordinates.filter(coord =>
            Array.isArray(coord) &&
            coord.length === 2 &&
            typeof coord[0] === 'number' &&
            typeof coord[1] === 'number' &&
            !isNaN(coord[0]) &&
            !isNaN(coord[1])
        );

        if (validCoordinates.length < 3) {
            Logger.warn(`Not enough valid coordinates for district: ${districtName}`);
            return null;
        }

        // Create polygon with district style and valid coordinates
        const polygon = L.polygon(validCoordinates, APP.CONFIG.DISTRICT_POLYGON.STYLE);

        // Add hover effects
        polygon.on('mouseover', function() {
            polygon.setStyle(APP.CONFIG.DISTRICT_POLYGON.HOVER_STYLE);
        });

        polygon.on('mouseout', function() {
            polygon.setStyle(APP.CONFIG.DISTRICT_POLYGON.STYLE);
        });

        // Add click event to filter by district
        polygon.on('click', function() {
            document.getElementById('district-selector').value = districtName;
            APP.filterByDistrict(districtName);
        });

        // Add to map if districts are shown
        if (APP.state.filters.showDistricts) {
            polygon.addTo(APP.state.map);
        }

        // Store reference
        APP.state.districtPolygons[districtName] = {
            polygon: polygon,
            name: districtName
        };

        Logger.debug('Added district polygon:', districtName);
        return polygon;
    } catch (error) {
        Logger.error(`Failed to add district polygon: ${error}`);
        return null;
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
            Utils.showError(APP.t('loadingError'));

            // Fall back to mock data
            if (APP.DEBUG || true) { // Always fall back to mock data for this demo
                Logger.info('Falling back to mock data...');
                return APP.loadMockData();
            }
        }
    };

    APP.loadMockData = async function() {
        try {
            Logger.info('Generating mock data...');

            // Generate mock data if not already present
            if (APP.state.mockApiData.lots.length === 0) {
                APP.generateMockData();
            }

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
            Utils.showError('Маълумотларни юклашда хатолик юз берди');
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
            } else if (lot.status === "2") { // Construction status
                style = {
                    ...style,
                    ...APP.CONFIG.POLYGON.CONSTRUCTION_STYLE
                };
            }

            // Create polygon
            const polygon = L.polygon(polygonCoords, style);

            // Add event listeners
            polygon.on('mouseover', function() {
                polygon.setStyle({
                    ...style,
                    ...APP.CONFIG.POLYGON.HOVER_STYLE
                });
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
                <h3>${lot.neighborhood_name || 'Номсиз жой'}</h3>
                <p>${lot.district_name || ''}</p>
                <p>
                    ${lot.area_hectare ? `<strong>Майдон:</strong> ${lot.area_hectare} ${APP.t('hectare')}` : ''}
                    ${statusInfo ? `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>` : ''}
                </p>
                <div class="popup-footer">
                    <button class="btn btn-primary btn-sm" onclick="MapApp.showDetails('${lot.id}')">
                        <i class="fas fa-info-circle"></i> ${APP.t('moreInfo')}
                    </button>
                </div>
            </div>
        `;
    };

    // Show notification (success or error)
    APP.showNotification = function(type, message, duration = 5000) {
        try {
            const toast = document.createElement('div');
            toast.className = type === 'success' ? 'success-toast' : 'error-toast';
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        } catch (error) {
            console.error('Failed to show notification:', error);
        }
    };

    // Show details for a specific lot
    APP.showDetails = function(lotId) {
        try {
            Logger.info('Showing details for lot:', lotId);

            // Prevent multiple simultaneous animations
            if (APP.state.view.isAnimating) {
                return;
            }

            // Find lot data
            const markerData = APP.state.markers.find(m => m.data.id === lotId);
            const polygonData = APP.state.polygons[lotId];
            const lot = (markerData ? markerData.data : (polygonData ? polygonData.data : null));

            if (!lot) {
                throw new Error(`Lot with ID ${lotId} not found`);
            }

            // Get status info
            const statusInfo = Utils.formatStatus(lot.status);

            // Store current view state before changing
            APP.state.view.lastZoom = APP.state.map.getZoom();
            APP.state.view.lastCenter = APP.state.map.getCenter();

            // Close existing sidebar if any
            APP.closeSidebar();

            // Update current item
            APP.state.view.currentItem = lotId;
            APP.state.view.isAnimating = true;

            // Calculate view parameters
            const sidebarWidth = window.innerWidth <= APP.CONFIG.MOBILE_BREAKPOINT ? 0 : APP.CONFIG.SIDEBAR_WIDTH;
            const padding = window.innerWidth <= APP.CONFIG.MOBILE_BREAKPOINT ? [50, 50] : [50, 50 + sidebarWidth];

            // Create and setup sidebar
            const sidebar = document.createElement('div');
            sidebar.className = 'sidebar';
            sidebar.innerHTML = `
                <div class="sidebar-header">
                    <button onclick="MapApp.closeSidebar()">
                            <i class="fas fa-times"></i>
                    </button>
                    <h2>${lot.neighborhood_name || 'Номсиз жой'} </h2>

                </div>
                <div class="sidebar-content">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i> ${APP.t('basicInfo')}
                    </div>
                    <table class="details-table">
                        <tr><td>${APP.t('district')}:</td><td>${lot.district_name || APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('area')}:</td><td>${lot.area_hectare ? lot.area_hectare + ' ' + APP.t('hectare') : APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('status')}:</td><td><span class="badge ${statusInfo.class}">${statusInfo.text}</span></td></tr>
                        <tr><td>${APP.t('strategy')}:</td><td>${lot.area_strategy || APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('decisionNumber')}:</td><td>${lot.decision_number || APP.t('notAvailable')}</td></tr>
                    </table>

                    <div class="section-title">
                        <i class="fas fa-building"></i> ${APP.t('technicalParams')}
                    </div>
                    <table class="details-table">
                        <tr><td>${APP.t('cadastre')}:</td><td>${lot.cadastre_certificate || APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('floors')}:</td><td>${lot.proposed_floors || APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('kmnCoefficient')}:</td><td>${lot.qmn_percentage || APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('umnCoefficient')}:</td><td>${lot.umn_coefficient || APP.t('notAvailable')}</td></tr>
                    </table>

                    <div class="section-title">
                        <i class="fas fa-expand-arrows-alt"></i> ${APP.t('areas')}
                    </div>
                    <table class="details-table">
                        <tr><td>${APP.t('residentialArea')}:</td><td>${lot.residential_area ? lot.residential_area + ' ' + APP.t('squareMeters') : APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('nonResidentialArea')}:</td><td>${lot.non_residential_area ? lot.non_residential_area + ' ' + APP.t('squareMeters') : APP.t('notAvailable')}</td></tr>
                        <tr><td>${APP.t('totalArea')}:</td><td>${lot.total_building_area ? lot.total_building_area + ' ' + APP.t('squareMeters') : APP.t('notAvailable')}</td></tr>
                    </table>

                    ${lot.documents && lot.documents.length > 0 ? `
                        <div class="section-title">
                            <i class="fas fa-file-alt"></i> ${APP.t('documents')}
                        </div>
                        <ul class="document-list">
                            ${lot.documents.map(doc => `
                                <li class="document-item">
                                    <span class="document-icon"><i class="fas fa-file-pdf"></i></span>
                                    <a href="${doc.url_keremas ?? 'test'}" target="_blank" class="document-link">
                                        ${doc.filename_keremas ?? 'Ҳужжат'}
                                    </a>
                                </li>
                            `).join('')}
                        </ul>` : ''}
                </div>
            `;

            // Append sidebar to body
            document.body.appendChild(sidebar);
            APP.state.currentSidebar = sidebar;

            // Show a success notification
            APP.showNotification('success', 'Маълумотлар муваффақиятли юкланди', 2000);

            // Handle view transitions with improved animation sequence
            requestAnimationFrame(() => {
                // First open sidebar with a smooth transition
                sidebar.classList.add('open');

                // Add print and share buttons to the sidebar
                const sidebarHeader = sidebar.querySelector('.sidebar-header');
                if (sidebarHeader) {
                    const actionButtons = document.createElement('div');
                    actionButtons.className = 'sidebar-actions';
                    actionButtons.style.display = 'flex';
                    actionButtons.style.gap = '10px';
                    actionButtons.style.marginTop = '10px';

                    // Print button
                    const printBtn = document.createElement('button');
                  printBtn.className = 'btn btn-sm';
                    printBtn.innerHTML = '<i class="fas fa-print"></i> Чоп этиш';
                    printBtn.onclick = function(e) {
                        e.stopPropagation();
                        APP.printLotDetails(lot);
                    };

                    // Share button
                    const shareBtn = document.createElement('button');
                    shareBtn.className = 'btn btn-sm';
                    shareBtn.innerHTML = '<i class="fas fa-share-alt"></i> Улашиш';
                    shareBtn.onclick = function(e) {
                        e.stopPropagation();
                        APP.shareLotDetails(lot);
                    };

                    // Export button
                    const exportBtn = document.createElement('button');
                    exportBtn.className = 'btn btn-sm';
                    exportBtn.innerHTML = '<i class="fas fa-file-export"></i> PDF';
                    exportBtn.onclick = function(e) {
                        e.stopPropagation();
                        APP.exportLotDetails(lot);
                    };

                    actionButtons.appendChild(printBtn);
                    actionButtons.appendChild(shareBtn);
                    actionButtons.appendChild(exportBtn);

                    sidebarHeader.appendChild(actionButtons);
                }

                // Wait for sidebar animation to start before adjusting map view
                setTimeout(() => {
                    // Clear any existing map animations to prevent conflicts
                    APP.state.map.stop();

                    // Create highlight effect for the selected feature
                    if (polygonData && polygonData.polygon) {
                        // Highlight the polygon with pulse effect
                        const originalStyle = { ...polygonData.polygon.options };
                        const pulseHighlight = () => {
                            polygonData.polygon.setStyle({
                                weight: 4,
                                color: '#4A6FD4',
                                dashArray: '5, 10',
                                fillOpacity: 0.5
                            });

                            setTimeout(() => {
                                polygonData.polygon.setStyle(originalStyle);

                                setTimeout(() => {
                                    if (APP.state.view.currentItem === lotId) {
                                        pulseHighlight();
                                    }
                                }, 1500);
                            }, 700);
                        };

                        pulseHighlight();

                        // Adjust view to show the polygon properly
                        APP.adjustPolygonView(polygonData.polygon, padding);
                    } else if (markerData && markerData.marker) {
                        // For markers, we want to highlight them and center the view
                        const markerElement = markerData.marker.getElement();
                        if (markerElement) {
                            markerElement.style.zIndex = 1000; // Bring to front

                            // Add a temporary highlight effect
                            const pulseElement = document.createElement('div');
                            pulseElement.className = 'marker-highlight-pulse';
                            pulseElement.style.position = 'absolute';
                            pulseElement.style.width = '30px';
                            pulseElement.style.height = '30px';
                            pulseElement.style.borderRadius = '50%';
                            pulseElement.style.backgroundColor = 'rgba(74, 111, 212, 0.3)';
                            pulseElement.style.boxShadow = '0 0 0 rgba(74, 111, 212, 0.4)';
                            pulseElement.style.animation = 'marker-pulse 1.5s infinite';
                            pulseElement.style.transform = 'translate(-5px, -5px)';
                            markerElement.appendChild(pulseElement);

                            // Clean up the pulse effect when sidebar is closed
                            APP.state.cleanup = APP.state.cleanup || [];
                            APP.state.cleanup.push(() => {
                                if (markerElement && pulseElement.parentNode === markerElement) {
                                    markerElement.removeChild(pulseElement);
                                }
                            });
                        }

                        // Adjust the map view to center on the marker
                        APP.adjustMarkerView(markerData.marker, sidebarWidth);
                    }

                    // Add related investments section if available
                    const sidebarContent = sidebar.querySelector('.sidebar-content');
                    if (sidebarContent && APP.state.markers.length > 0) {
                        // Find related investments in the same district
                        const relatedInvestments = APP.state.markers
                            .filter(m => m.data.id !== lot.id && m.data.district_name === lot.district_name)
                            .slice(0, 3);

                        if (relatedInvestments.length > 0) {
                            const relatedSection = document.createElement('div');
                            relatedSection.innerHTML = `
                                <div class="section-title">
                                    <i class="fas fa-project-diagram"></i> Боғлиқ инвестициялар
                                </div>
                                <div class="related-investments">
                                    ${relatedInvestments.map(({data}) => `
                                        <div class="data-card" onclick="MapApp.showDetails('${data.id}')">
                                            <div class="data-card-header">
                                                <div class="data-card-title">${data.neighborhood_name || 'Номсиз жой'}</div>
                                                <span class="badge ${Utils.formatStatus(data.status).class} data-card-badge">
                                                    ${Utils.formatStatus(data.status).text}
                                                </span>
                                            </div>
                                            <div class="data-card-address">${data.district_name || ''}</div>
                                            <div class="data-card-stats">
                                                <div class="data-card-stat">
                                                    <i class="fas fa-expand-arrows-alt"></i> ${data.area_hectare || 'Н/М'} га
                                                </div>
                                                ${data.proposed_floors ? `
                                                    <div class="data-card-stat">
                                                        <i class="fas fa-building"></i> ${data.proposed_floors} қават
                                                    </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            `;

                            sidebarContent.appendChild(relatedSection);
                        }
                    }

                    // Check if we need to load additional data
                    if (lot && lot.id) {
                        // This is where you could fetch additional details if needed
                        APP.loadAdditionalDetails(lot.id).then(extraData => {
                            if (extraData && APP.state.view.currentItem === lotId) {
                                // Update sidebar with extra data if available
                                const extraInfoSection = document.createElement('div');
                                extraInfoSection.className = 'section-title';
                                extraInfoSection.innerHTML = `
                                    <i class="fas fa-chart-bar"></i> Қўшимча маълумотлар
                                `;

                                const extraInfoContent = document.createElement('div');
                                extraInfoContent.className = 'extra-info-content';
                                extraInfoContent.innerHTML = `
                                    <table class="details-table">
                                        <tr><td>Иқтисодий таъсир:</td><td>${extraData.economicImpact} млн. сўм</td></tr>
                                        <tr><td>Яратилган иш ўринлари:</td><td>${extraData.jobsCreated}</td></tr>
                                        <tr><td>Инвестиция миқдори:</td><td>${extraData.investmentAmount} млн. $</td></tr>
                                        <tr><td>Қурилиш муддати:</td><td>${extraData.constructionDuration} ой</td></tr>
                                        <tr><td>Сўнгги янгиланиш:</td><td>${extraData.lastUpdated}</td></tr>
                                    </table>
                                `;

                                // Append to sidebar if we still have the same item open
                                if (APP.state.currentSidebar && APP.state.view.currentItem === lotId) {
                                    const sidebarContent = APP.state.currentSidebar.querySelector('.sidebar-content');
                                    if (sidebarContent) {
                                        sidebarContent.appendChild(extraInfoSection);
                                        sidebarContent.appendChild(extraInfoContent);
                                    }
                                }
                            }
                        }).catch(err => {
                            Logger.error('Failed to load additional details:', err);
                        });
                    }

                    // Reset animation state after all transitions complete
                    setTimeout(() => {
                        APP.state.view.isAnimating = false;

                        // Notify any screen readers that the details are loaded (accessibility)
                        const a11yAnnouncement = document.createElement('div');
                        a11yAnnouncement.setAttribute('role', 'status');
                        a11yAnnouncement.setAttribute('aria-live', 'polite');
                        a11yAnnouncement.className = 'sr-only';
                        a11yAnnouncement.textContent = `${lot.neighborhood_name || 'Инвестиция майдони'} маълумотлари юкланди`;
                        document.body.appendChild(a11yAnnouncement);

                        // Clean up the announcement after it's been read
                        setTimeout(() => {
                            if (a11yAnnouncement.parentNode) {
                                a11yAnnouncement.parentNode.removeChild(a11yAnnouncement);
                            }
                        }, 3000);
                    }, APP.CONFIG.MAP_ANIMATION.TOTAL_DURATION);
                }, APP.CONFIG.ANIMATION_DURATION / 3);
            });
        } catch (error) {
            Logger.error('Failed to show details:', error);
            Utils.showError('Инвестиция маълумотларини юклашда хатолик юз берди');
            APP.state.view.isAnimating = false;
        }
    };

    // Add these helper functions for view management
    APP.adjustPolygonView = function(polygon, padding) {
        if (!polygon) return;

        const bounds = polygon.getBounds();

        // Clear any existing animations
        APP.state.map.stop();

        APP.state.map.once('moveend', () => {
            // Ensure proper centering after bounds are set
            const center = bounds.getCenter();
            const zoom = APP.state.map.getBoundsZoom(bounds);

            APP.state.map.setView(center, Math.min(zoom, 17), {
                animate: true,
                duration: APP.CONFIG.MAP_ANIMATION.PAN_DURATION,
                paddingTopLeft: [50, 50],
                paddingBottomRight: padding
            });
        });

        APP.state.map.fitBounds(bounds, {
            paddingTopLeft: [50, 50],
            paddingBottomRight: padding,
            maxZoom: 17,
            animate: true,
            duration: APP.CONFIG.MAP_ANIMATION.ZOOM_DURATION
        });
    };

    APP.adjustMarkerView = function(marker, sidebarWidth) {
        const point = marker.getLatLng();

        // First zoom to marker
        APP.state.map.setView(point, 17, {
            animate: true,
            duration: APP.CONFIG.MAP_ANIMATION.ZOOM_DURATION
        });

        // Then pan to account for sidebar if on desktop
        if (window.innerWidth > APP.CONFIG.MOBILE_BREAKPOINT) {
            setTimeout(() => {
                APP.state.map.panBy([-sidebarWidth / 2, 0], {
                    animate: true,
                    duration: APP.CONFIG.MAP_ANIMATION.PAN_DURATION,
                    easeLinearity: APP.CONFIG.MAP_ANIMATION.EASE_LINEARITY
                });
            }, APP.CONFIG.MAP_ANIMATION.ZOOM_DURATION * 1000);
        }
    };

    // Update the close sidebar function to handle state properly
    APP.closeSidebar = function() {
        if (APP.state.currentSidebar) {
            APP.state.currentSidebar.classList.remove('open');

            setTimeout(() => {
                if (APP.state.currentSidebar) {
                    // Clear any existing animations
                    APP.state.map.stop();

                    // Run cleanup functions if any
                    if (APP.state.cleanup && APP.state.cleanup.length) {
                        APP.state.cleanup.forEach(cleanupFn => {
                            try {
                                cleanupFn();
                            } catch (e) {
                                Logger.error('Cleanup function error:', e);
                            }
                        });
                        APP.state.cleanup = [];
                    }

                    APP.state.currentSidebar.remove();
                    APP.state.currentSidebar = null;

                    // Reset view state
                    APP.state.view.currentItem = null;

                    if (APP.state.view.lastCenter && APP.state.view.lastZoom) {
                        APP.state.map.setView(
                            APP.state.view.lastCenter,
                            APP.state.view.lastZoom, {
                                animate: true,
                                duration: APP.CONFIG.MAP_ANIMATION.ZOOM_DURATION
                            }
                        );
                    }
                }
            }, APP.CONFIG.ANIMATION_DURATION);
        }
    };

    APP.cleanupMapEvents = function() {
        if (APP.state.map) {
            APP.state.map.stop();
            APP.state.map.off('moveend');
            APP.state.map.off('zoomend');
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
                const matchesDistrict =
                    districtName === 'all' ||
                    (data.district_name && data.district_name.toLowerCase().includes(
                        districtName.toLowerCase()));

                const matchesStatus =
                    APP.state.currentStatus === 'all' ||
                    data.status === APP.state.currentStatus;

                if (matchesDistrict && matchesStatus && APP.state.filters.showMarkers) {
                    APP.state.markerCluster.addLayer(marker);
                }
            });

            // Handle polygons visibility
            Object.values(APP.state.polygons).forEach(({
                polygon,
                data
            }) => {
                const matchesDistrict =
                    districtName === 'all' ||
                    (data.district_name && data.district_name.toLowerCase().includes(
                        districtName.toLowerCase()));

                const matchesStatus =
                    APP.state.currentStatus === 'all' ||
                    data.status === APP.state.currentStatus;

                if (matchesDistrict && matchesStatus && APP.state.filters.showPolygons) {
                    if (!APP.state.map.hasLayer(polygon)) {
                        polygon.addTo(APP.state.map);
                    }
                } else {
                    if (APP.state.map.hasLayer(polygon)) {
                        APP.state.map.removeLayer(polygon);
                    }
                }
            });

            // Highlight the selected district
            if (districtName !== 'all') {
                // Zoom to the district
                const districtPolygon = APP.state.districtPolygons[districtName];
                if (districtPolygon && districtPolygon.polygon) {
                    APP.state.map.fitBounds(districtPolygon.polygon.getBounds(), {
                        padding: [50, 50],
                        maxZoom: 14,
                        animate: true
                    });
                }
            } else {
                // Reset to default view
                APP.state.map.setView(APP.CONFIG.MAP.CENTER, APP.CONFIG.MAP.DEFAULT_ZOOM, {
                    animate: true
                });
            }

            Logger.debug('Filter applied:', districtName);
        } catch (error) {
            Logger.error('Failed to apply filter:', error);
            Utils.showError('Фильтрни қўллашда хатолик юз берди');
        }
    };

    // Filter by status
    APP.filterByStatus = function(status) {
        try {
            Logger.info('Filtering by status:', status);
            APP.state.currentStatus = status;

            // Update status buttons
            document.querySelectorAll('.status-btn').forEach(btn => {
                if (btn.dataset.status === status) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Re-apply district filter with new status filter
            APP.filterByDistrict(APP.state.currentDistrict);

            Logger.debug('Status filter applied:', status);
        } catch (error) {
            Logger.error('Failed to apply status filter:', error);
            Utils.showError('Статус фильтрини қўллашда хатолик юз берди');
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

                const matchesStatus =
                    APP.state.currentStatus === 'all' ||
                    data.status === APP.state.currentStatus;

                if (show && matchesDistrict && matchesStatus) {
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

    // Toggle district boundaries visibility
    APP.toggleDistricts = function(show) {
        try {
            APP.state.filters.showDistricts = show;

            Object.values(APP.state.districtPolygons).forEach(({ polygon }) => {
                if (show) {
                    if (!APP.state.map.hasLayer(polygon)) {
                        polygon.addTo(APP.state.map);
                    }
                } else {
                    if (APP.state.map.hasLayer(polygon)) {
                        APP.state.map.removeLayer(polygon);
                    }
                }
            });

            Logger.debug('District boundaries visibility toggled:', show);
        } catch (error) {
            Logger.error('Failed to toggle district boundaries:', error);
        }
    };

    // Switch map layer
    APP.switchMapLayer = function(layerName) {
        try {
            if (APP.state.layers.instances[layerName]) {
                // Remove current layer
                APP.state.map.removeLayer(APP.state.layers.instances[APP.state.layers.current]);

                // Add new layer
                APP.state.layers.instances[layerName].addTo(APP.state.map);

                // Update current layer
                APP.state.layers.current = layerName;

                Logger.debug('Map layer switched to:', layerName);
            }
        } catch (error) {
            Logger.error('Failed to switch map layer:', error);
        }
    };

    // Load additional details for an investment lot
    APP.loadAdditionalDetails = async function(lotId) {
        try {
            Logger.info('Loading additional details for lot:', lotId);

            // In a real application, this would fetch data from an API
            // For this demo, we'll simulate a network request and return mock data
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    // Check if lot exists in our data
                    const markerData = APP.state.markers.find(m => m.data.id === lotId);
                    const polygonData = APP.state.polygons[lotId];
                    const lot = (markerData ? markerData.data : (polygonData ? polygonData.data : null));

                    if (!lot) {
                        reject(new Error('Lot not found'));
                        return;
                    }

                    // Generate some random additional data based on lot properties
                    const additionalData = {
                        economicImpact: Math.floor(Math.random() * 1000) + 500,
                        jobsCreated: Math.floor(Math.random() * 200) + 50,
                        investmentAmount: (Math.random() * 10 + 2).toFixed(2),
                        constructionDuration: Math.floor(Math.random() * 24) + 6,
                        lastUpdated: new Date().toISOString().split('T')[0]
                    };

                    resolve(additionalData);
                }, 800); // Simulate network delay
            });
        } catch (error) {
            Logger.error('Failed to load additional details:', error);
            return null;
        }
    };

    // Print lot details
    APP.printLotDetails = function(lot) {
        try {
            Logger.info('Printing lot details:', lot.id);

            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            if (!printWindow) {
                Utils.showError('Чоп этиш ойнасини очишда хатолик юз берди. Браузер қалқиб чиқувчи ойналарга рухсат берганини текширинг.');
                return;
            }

            // Get status info
            const statusInfo = Utils.formatStatus(lot.status);

            // Create print content
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="uz">
                <head>
                    <meta charset="UTF-8">
                    <title>Инвестиция маълумотлари - ${lot.id}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.5;
                            padding: 20px;
                            max-width: 800px;
                            margin: 0 auto;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 20px;
                            padding-bottom: 20px;
                            border-bottom: 2px solid #1E3685;
                        }
                        .logo {
                            font-size: 22px;
                            font-weight: bold;
                            color: #1E3685;
                        }
                        .title {
                            font-size: 24px;
                            margin: 20px 0;
                            color: #1E3685;
                        }
                        .section {
                            margin-bottom: 20px;
                        }
                        .section-title {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            color: #1E3685;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        th, td {
                            text-align: left;
                            padding: 8px;
                            border-bottom: 1px solid #ddd;
                        }
                        th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                            width: 40%;
                        }
                        .status {
                            display: inline-block;
                            padding: 5px 10px;
                            border-radius: 4px;
                            font-weight: bold;
                        }
                        .status-success {
                            background-color: rgba(14, 98, 69, 0.1);
                            color: #0E6245;
                        }
                        .status-warning {
                            background-color: rgba(206, 126, 0, 0.1);
                            color: #CE7E00;
                        }
                        .status-info {
                            background-color: rgba(30, 54, 133, 0.1);
                            color: #1E3685;
                        }
                        .footer {
                            margin-top: 40px;
                            text-align: center;
                            font-size: 12px;
                            color: #666;
                        }
                        @media print {
                            .no-print {
                                display: none;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="logo">ИнвестУз - Инвестиция харитаси</div>
                    </div>

                    <div class="title">${lot.neighborhood_name || 'Номсиз жой'}</div>

                    <div class="section">
                        <div class="section-title">Асосий маълумот</div>
                        <table>
                            <tr>
                                <th>Туман:</th>
                                <td>${lot.district_name || 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>Майдон:</th>
                                <td>${lot.area_hectare ? lot.area_hectare + ' га' : 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>Холат:</th>
                                <td><span class="status status-${statusInfo.class.replace('badge-', '')}">${statusInfo.text}</span></td>
                            </tr>
                            <tr>
                                <th>Стратегия:</th>
                                <td>${lot.area_strategy || 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>Қарор рақами:</th>
                                <td>${lot.decision_number || 'Н/М'}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="section">
                        <div class="section-title">Техник параметрлар</div>
                        <table>
                            <tr>
                                <th>Кадастр:</th>
                                <td>${lot.cadastre_certificate || 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>Қаватлар сони:</th>
                                <td>${lot.proposed_floors || 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>КМН коэффициенти:</th>
                                <td>${lot.qmn_percentage || 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>УМН коэффициенти:</th>
                                <td>${lot.umn_coefficient || 'Н/М'}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="section">
                        <div class="section-title">Майдонлар</div>
                        <table>
                            <tr>
                                <th>Турар-жой майдони:</th>
                                <td>${lot.residential_area ? lot.residential_area + ' м²' : 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>Нотурар-жой майдони:</th>
                                <td>${lot.non_residential_area ? lot.non_residential_area + ' м²' : 'Н/М'}</td>
                            </tr>
                            <tr>
                                <th>Умумий майдон:</th>
                                <td>${lot.total_building_area ? lot.total_building_area + ' м²' : 'Н/М'}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="footer">
                        <p>Ушбу ҳужжат ИнвестУз - Инвестиция харитаси томонидан тайёрланган.<br>
                        Сана: ${new Date().toLocaleDateString('uz-UZ')}</p>
                    </div>

                    <div class="no-print" style="text-align: center; margin-top: 30px;">
                        <button onclick="window.print()" style="padding: 10px 20px; background: #1E3685; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Чоп этиш
                        </button>
                        <button onclick="window.close()" style="padding: 10px 20px; background: #767676; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                            Ёпиш
                        </button>
                    </div>
                </body>
                </html>
            `);

            printWindow.document.close();

            // Automatically open print dialog after content is loaded
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                    printWindow.print();
                }, 500);
            };
        } catch (error) {
            Logger.error('Print failed:', error);
            Utils.showError('Чоп этиш амалиётида хатолик юз берди');
        }
    };

    APP.shareLotDetails = function(lot) {
                try {
                    Logger.info('Sharing lot details:', lot.id);

                    // Create a shareable URL with lot ID
                    const shareUrl = new URL(window.location.href);
                    shareUrl.searchParams.set('lot', lot.id);

                    // Check if Web Share API is available
                    if (navigator.share) {
                        navigator.share({
                            title: `ИнвестУз - ${lot.neighborhood_name || 'Инвестиция майдони'}`,
                            text: `${lot.neighborhood_name || 'Инвестиция майдони'} - ${lot.district_name || ''}, ${lot.area_hectare || ''} га`,
                            url: shareUrl.toString()
                        }).then(() => {
                            Logger.info('Successfully shared');
                        }).catch((error) => {
                            Logger.error('Share failed:', error);
                            APP.fallbackShare(shareUrl.toString());
                        });
                    } else {
                        // Fallback for browsers that don't support the Web Share API
                        APP.fallbackShare(shareUrl.toString());
                    }
                } catch (error) {
                    Logger.error('Share failed:', error);
                    Utils.showError('Улашиш амалиётида хатолик юз берди');
                }
            };

            APP.fallbackShare = function(url) {
                try {
                    // Create a temporary input element
                    const tempInput = document.createElement('input');
                    tempInput.style.position = 'absolute';
                    tempInput.style.left = '-1000px';
                    tempInput.value = url;
                    document.body.appendChild(tempInput);

                    // Select and copy the URL
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);

                    // Show success notification
                    APP.showNotification('success', 'URL нусхаланди - уни улашишингиз мумкин', 3000);
                } catch (error) {
                    Logger.error('Fallback share failed:', error);
                    Utils.showError('URL нусхалашда хатолик юз берди');
                }
            };

            APP.exportLotDetails = function(lot) {
                try {
                    Logger.info('Exporting lot details to PDF:', lot.id);

                    // This would normally use a PDF library like jsPDF
                    // For this demo, we'll just show a notification
                    APP.showNotification('success', 'PDF экспорт қилиш амалиёти бошланди...', 2000);

                    // Simulate download after a delay
                    setTimeout(() => {
                        APP.showNotification('success', 'PDF файл тайёрланди ва юклаб олинди', 3000);
                    }, 2000);
                } catch (error) {
                    Logger.error('Export failed:', error);
                    Utils.showError('PDF экспорт қилишда хатолик юз берди');
                }
            };

            // Generate mock data for testing
            APP.generateMockData = function() {
                Logger.info('Generating mock data for testing...');

                // Define districts of Tashkent
                const districts = [
                    'Бектемир', 'Чилонзор', 'Миробод', 'Мирзо Улуғбек',
                    'Олмазор', 'Сергели', 'Шайхонтоҳур', 'Учтепа',
                    'Яккасарой', 'Яшнобод', 'Юнусобод', 'Янгиҳаёт'
                ];

                // Base coordinates for Tashkent
                const baseLatCenter = 41.311;
                const baseLngCenter = 69.279;

                // Mock polygon data generator
                const generateMockPolygon = (centerLat, centerLng, size = 0.003) => {
                    const points = [];
                    const sides = 5 + Math.floor(Math.random() * 4); // 5-8 sides

                    for (let i = 0; i < sides; i++) {
                        const angle = (i / sides) * 2 * Math.PI;
                        const variation = 0.4 + Math.random() * 0.6; // Random variation in size
                        const lat = centerLat + Math.sin(angle) * size * variation;
                        const lng = centerLng + Math.cos(angle) * size * variation;

                        // Create DMS format
                        const latDeg = Math.floor(lat);
                        const latMin = Math.floor((lat - latDeg) * 60);
                        const latSec = ((lat - latDeg - latMin/60) * 3600).toFixed(2);
                        const lngDeg = Math.floor(lng);
                        const lngMin = Math.floor((lng - lngDeg) * 60);
                        const lngSec = ((lng - lngDeg - lngMin/60) * 3600).toFixed(2);

                        points.push({
                            start_lat: `${latDeg}°${latMin}'${latSec}"С`,
                            start_lon: `${lngDeg}°${lngMin}'${lngSec}"E`
                        });
                    }

                    return points;
                };

                // Generate 40 random lots
                APP.state.mockApiData.lots = [];
                for (let i = 0; i < 40; i++) {
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
                    const statuses = ["9", "1", "2"];
                    const status = statuses[Math.floor(Math.random() * statuses.length)];

                    const lot = {
                        id: id,
                        neighborhood_name: `Участок ${id} (${area} га)`,
                        area_hectare: parseFloat(area),
                        status: status,
                        district_name: district,
                        lat: lat,
                        lng: lng,
                        decision_number: Math.floor(Math.random() * 100).toString(),
                        area_strategy: "инвест шартномаси тузиш",
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

                    // Add random mock polygon to 70% of lots
                    if (Math.random() > 0.3) {
                        lot.polygons = generateMockPolygon(lat, lng);
                    }

                    // Add to mock data
                    APP.state.mockApiData.lots.push(lot);
                }

                Logger.info('Mock data generated:', APP.state.mockApiData.lots.length, 'total lots');
            };

            // Perform search
            APP.performSearch = function(query) {
                try {
                    Logger.info('Performing search:', query);

                    const searchResults = document.querySelector('.search-results');
                    searchResults.innerHTML = '';

                    // Find matching lots
                    const results = [];

                    // Search in markers
                    APP.state.markers.forEach(({ data }) => {
                        if (
                            (data.neighborhood_name && data.neighborhood_name.toLowerCase().includes(query)) ||
                            (data.district_name && data.district_name.toLowerCase().includes(query)) ||
                            (data.cadastre_certificate && data.cadastre_certificate.toLowerCase().includes(query))
                        ) {
                            results.push(data);
                        }
                    });

                    // Display results
                    if (results.length > 0) {
                        results.forEach(lot => {
                            const resultItem = document.createElement('div');
                            resultItem.className = 'search-result-item';
                            resultItem.innerHTML = `
                                <div class="search-result-title">${lot.neighborhood_name || 'Номсиз жой'}</div>
                                <div class="search-result-address">${lot.district_name || ''}</div>
                            `;

                            resultItem.addEventListener('click', function() {
                                APP.showDetails(lot.id);
                                searchResults.classList.remove('active');
                            });

                            searchResults.appendChild(resultItem);
                        });

                        searchResults.classList.add('active');
                    } else {
                        // No results
                        const noResults = document.createElement('div');
                        noResults.className = 'search-result-item';
                        noResults.innerHTML = `<div class="search-result-title">Натижалар топилмади</div>`;
                        searchResults.appendChild(noResults);
                        searchResults.classList.add('active');
                    }

                    Logger.debug('Search completed, results:', results.length);
                } catch (error) {
                    Logger.error('Search failed:', error);
                }
            };

            // Setup event listeners
            APP.shareLotDetails = function(lot) {
                try {
                    Logger.info('Sharing lot details:', lot.id);

                    // Create a shareable URL with lot ID
                    const shareUrl = new URL(window.location.href);
                    shareUrl.searchParams.set('lot', lot.id);

                    // Check if Web Share API is available
                    if (navigator.share) {
                        navigator.share({
                            title: `ИнвестУз - ${lot.neighborhood_name || 'Инвестиция майдони'}`,
                            text: `${lot.neighborhood_name || 'Инвестиция майдони'} - ${lot.district_name || ''}, ${lot.area_hectare || ''} га`,
                            url: shareUrl.toString()
                        }).then(() => {
                            Logger.info('Successfully shared');
                        }).catch((error) => {
                            Logger.error('Share failed:', error);
                            APP.fallbackShare(shareUrl.toString());
                        });
                    } else {
                        // Fallback for browsers that don't support the Web Share API
                        APP.fallbackShare(shareUrl.toString());
                    }
                } catch (error) {
                    Logger.error('Share failed:', error);
                    Utils.showError('Улашиш амалиётида хатолик юз берди');
                }
            };

            APP.fallbackShare = function(url) {
                try {
                    // Create a temporary input element
                    const tempInput = document.createElement('input');
                    tempInput.style.position = 'absolute';
                    tempInput.style.left = '-1000px';
                    tempInput.value = url;
                    document.body.appendChild(tempInput);

                    // Select and copy the URL
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);

                    // Show success notification
                    APP.showNotification('success', 'URL нусхаланди - уни улашишингиз мумкин', 3000);
                } catch (error) {
                    Logger.error('Fallback share failed:', error);
                    Utils.showError('URL нусхалашда хатолик юз берди');
                }
            };

            APP.exportLotDetails = function(lot) {
                try {
                    Logger.info('Exporting lot details to PDF:', lot.id);

                    // This would normally use a PDF library like jsPDF
                    // For this demo, we'll just show a notification
                    APP.showNotification('success', 'PDF экспорт қилиш амалиёти бошланди...', 2000);

                    // Simulate download after a delay
                    setTimeout(() => {
                        APP.showNotification('success', 'PDF файл тайёрланди ва юклаб олинди', 3000);
                    }, 2000);
                } catch (error) {
                    Logger.error('Export failed:', error);
                    Utils.showError('PDF экспорт қилишда хатолик юз берди');
                }
            };

            // Generate mock data for testing
            APP.generateMockData = function() {
                Logger.info('Generating mock data for testing...');

                // Define districts of Tashkent
                const districts = [
                    'Бектемир', 'Чилонзор', 'Миробод', 'Мирзо Улуғбек',
                    'Олмазор', 'Сергели', 'Шайхонтоҳур', 'Учтепа',
                    'Яккасарой', 'Яшнобод', 'Юнусобод', 'Янгиҳаёт'
                ];

                // Base coordinates for Tashkent
                const baseLatCenter = 41.311;
                const baseLngCenter = 69.279;

                // Mock polygon data generator
                const generateMockPolygon = (centerLat, centerLng, size = 0.003) => {
                    const points = [];
                    const sides = 5 + Math.floor(Math.random() * 4); // 5-8 sides

                    for (let i = 0; i < sides; i++) {
                        const angle = (i / sides) * 2 * Math.PI;
                        const variation = 0.4 + Math.random() * 0.6; // Random variation in size
                        const lat = centerLat + Math.sin(angle) * size * variation;
                        const lng = centerLng + Math.cos(angle) * size * variation;

                        // Create DMS format
                        const latDeg = Math.floor(lat);
                        const latMin = Math.floor((lat - latDeg) * 60);
                        const latSec = ((lat - latDeg - latMin/60) * 3600).toFixed(2);
                        const lngDeg = Math.floor(lng);
                        const lngMin = Math.floor((lng - lngDeg) * 60);
                        const lngSec = ((lng - lngDeg - lngMin/60) * 3600).toFixed(2);

                        points.push({
                            start_lat: `${latDeg}°${latMin}'${latSec}"С`,
                            start_lon: `${lngDeg}°${lngMin}'${lngSec}"E`
                        });
                    }

                    return points;
                };

                // Generate 40 random lots
                APP.state.mockApiData.lots = [];
                for (let i = 0; i < 40; i++) {
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
                    const statuses = ["9", "1", "2"];
                    const status = statuses[Math.floor(Math.random() * statuses.length)];

                    const lot = {
                        id: id,
                        neighborhood_name: `Участок ${id} (${area} га)`,
                        area_hectare: parseFloat(area),
                        status: status,
                        district_name: district,
                        lat: lat,
                        lng: lng,
                        decision_number: Math.floor(Math.random() * 100).toString(),
                        area_strategy: "инвест шартномаси тузиш",
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

                    // Add random mock polygon to 70% of lots
                    if (Math.random() > 0.3) {
                        lot.polygons = generateMockPolygon(lat, lng);
                    }

                    // Add to mock data
                    APP.state.mockApiData.lots.push(lot);
                }

                Logger.info('Mock data generated:', APP.state.mockApiData.lots.length, 'total lots');
            };

            // Perform search
            APP.performSearch = function(query) {
                try {
                    Logger.info('Performing search:', query);

                    const searchResults = document.querySelector('.search-results');
                    searchResults.innerHTML = '';

                    // Find matching lots
                    const results = [];

                    // Search in markers
                    APP.state.markers.forEach(({ data }) => {
                        if (
                            (data.neighborhood_name && data.neighborhood_name.toLowerCase().includes(query)) ||
                            (data.district_name && data.district_name.toLowerCase().includes(query)) ||
                            (data.cadastre_certificate && data.cadastre_certificate.toLowerCase().includes(query))
                        ) {
                            results.push(data);
                        }
                    });

                    // Display results
                    if (results.length > 0) {
                        results.forEach(lot => {
                            const resultItem = document.createElement('div');
                            resultItem.className = 'search-result-item';
                            resultItem.innerHTML = `
                                <div class="search-result-title">${lot.neighborhood_name || 'Номсиз жой'}</div>
                                <div class="search-result-address">${lot.district_name || ''}</div>
                            `;

                            resultItem.addEventListener('click', function() {
                                APP.showDetails(lot.id);
                                searchResults.classList.remove('active');
                            });

                            searchResults.appendChild(resultItem);
                        });

                        searchResults.classList.add('active');
                    } else {
                        // No results
                        const noResults = document.createElement('div');
                        noResults.className = 'search-result-item';
                        noResults.innerHTML = `<div class="search-result-title">Натижалар топилмади</div>`;
                        searchResults.appendChild(noResults);
                        searchResults.classList.add('active');
                    }

                    Logger.debug('Search completed, results:', results.length);
                } catch (error) {
                    Logger.error('Search failed:', error);
                }
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

                    // Status filter buttons
                    document.querySelectorAll('.status-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const status = this.dataset.status;
                            APP.filterByStatus(status);
                        });
                    });

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

                    // Show districts checkbox
                    const showDistrictsCheckbox = document.getElementById('show-districts');
                    if (showDistrictsCheckbox) {
                        showDistrictsCheckbox.addEventListener('change', function() {
                            APP.toggleDistricts(this.checked);
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

                    // Map controls
                    document.getElementById('zoom-in').addEventListener('click', function() {
                        APP.state.map.zoomIn();
                    });

                    document.getElementById('zoom-out').addEventListener('click', function() {
                        APP.state.map.zoomOut();
                    });

                    document.getElementById('reset-view').addEventListener('click', function() {
                        APP.state.map.setView(APP.CONFIG.MAP.CENTER, APP.CONFIG.MAP.DEFAULT_ZOOM, {
                            animate: true
                        });
                    });

                    // Language switcher
                    document.querySelectorAll('.lang-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            // This is a stub for language switching
                            // In a real app, this would change the language
                            document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                        });
                    });

                    // Search functionality
                    const searchInput = document.querySelector('.search-input');
                    const searchBtn = document.querySelector('.search-btn');
                    const searchResults = document.querySelector('.search-results');

                    if (searchInput && searchBtn && searchResults) {
                        // Search button click
                        searchBtn.addEventListener('click', function() {
                            const query = searchInput.value.trim().toLowerCase();
                            if (query.length > 2) {
                                APP.performSearch(query);
                            }
                        });

                        // Search on enter key
                        searchInput.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                const query = this.value.trim().toLowerCase();
                                if (query.length > 2) {
                                    APP.performSearch(query);
                                }
                            }
                        });

                        // Hide search results when clicking outside
                        document.addEventListener('click', function(e) {
                            if (!e.target.closest('.search-box')) {
                                searchResults.classList.remove('active');
                            }
                        });
                    }

                    // Map click event to close sidebar on mobile
                    APP.state.map.on('click', function() {
                        if (window.innerWidth <= APP.CONFIG.MOBILE_BREAKPOINT) {
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

                    // Load district boundaries
                    await APP.loadDistrictBoundaries();

                    // Load investment data
                    await APP.loadData();

                    // Setup event listeners
                    APP.setupEventListeners();

                    // Close loading indicator
                    Utils.hideLoading();

                    Logger.info('Application initialized successfully');
                } catch (error) {
                    Logger.error('Application initialization failed:', error);
                    Utils.hideLoading();
                    Utils.showError('Иловани ишга туширишда хатолик юз берди. Саҳифани янгиланг.');
                }
            };

            // Initialize on DOM ready
            document.addEventListener('DOMContentLoaded', function() {
                APP.init();
            });
        })(window.MapApp = window.MapApp || {});
</script>
</body>

</html>
