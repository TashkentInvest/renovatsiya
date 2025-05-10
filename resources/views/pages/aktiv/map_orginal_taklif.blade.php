<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvestUz Map</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/map_style/main.css') }}">

    {{-- <style>
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            width: 100%;
        }

        #map {
            height: 100vh;
            width: 100%;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            transition: right 0.3s;
            z-index: 1000;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .sidebar-close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .sidebar-content {
            padding: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .details-table td:first-child {
            width: 50%;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            background: #f0f0f0;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .related-investments {
            margin-top: 10px;
        }

        .data-card {
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .data-card:hover {
            background: #f9f9f9;
        }

        .data-card-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

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
            z-index: 2000;
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            border-radius: 4px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 2000;
        }

        .document-link {
            display: inline-block;
            padding: 5px 10px;
            margin-top: 5px;
            background: #f0f0f0;
            border-radius: 3px;
            text-decoration: none;
            color: #333;
        }

        .document-link:hover {
            background: #e0e0e0;
        }

        .documents-list {
            margin-bottom: 15px;
        }

        .document-link i {
            margin-right: 5px;
        }

        .main-image {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 15px;
            max-height: 200px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                right: -100%;
            }
        }
    </style> --}}
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
            <button class="lang-btn">EN</button>
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

    <script>
        // App namespace
        const App = {
            map: null,
            markers: [],
            polygons: {},
            markerCluster: null,
            currentSidebar: null,
            isAnimating: false,
            currentItem: null,
            lastView: {
                center: null,
                zoom: null
            },
            cleanup: [],
            apiBaseUrl: 'http://127.0.0.1:8000' // Your API base URL
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

        // Show details
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

            // Search in markers
            for (let i = 0; i < App.markers.length; i++) {
                if (App.markers[i].data && App.markers[i].data.id === lotId) {
                    markerEntry = App.markers[i];
                    lot = markerEntry.data;
                    break;
                }
            }

            // If not found in markers, check polygons
            if (!lot && App.polygons[lotId]) {
                polygonEntry = App.polygons[lotId];
                lot = polygonEntry.data;
            }

            // Validate lot data
            if (!lot) {
                console.error(`Lot with ID ${lotId} not found`);
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

            // Add documents section if available
            if (lot.documents && lot.documents.length > 0) {
                sidebarHtml += `
                    <div class="section-title">Ҳужжатлар</div>
                    <div class="documents-list">
                `;

                lot.documents.forEach(doc => {
                    sidebarHtml += `
                        <div class="data-card">
                            <div class="data-card-title">${doc.filename || 'Ҳужжат'}</div>
                            <div>Тури: ${doc.doc_type || 'Кўрсатилмаган'}</div>
                            ${doc.url ? `<a href="${doc.url}" target="_blank" class="document-link">Ҳужжатни кўриш</a>` : ''}
                        </div>
                    `;
                });

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
                    showToast('No data found in API response. Using demo data.', 'warning');
                    // useDemoData();
                    return;
                }

                // Process the lots data
                let processedCount = 0;

                // Generate an ID counter for lots without IDs
                let idCounter = 1;

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
                });

                if (processedCount > 0) {
                    showToast(`Successfully loaded ${processedCount} items`, 'info');

                    // Fit map to all markers
                    if (App.markers.length > 0) {
                        const group = L.featureGroup(App.markers.map(m => m.marker));
                        App.map.fitBounds(group.getBounds(), {
                            padding: [50, 50]
                        });
                    }
                } else {
                    console.warn('No valid items were processed from API data');
                    showToast('No valid items found in data. Using demo data.', 'warning');
                    // useDemoData();
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                showToast('Error loading data: ' + error.message + '. Using demo data.', 'error');
                // useDemoData();
            } finally {
                hideLoading();
            }
        }

        // Create and use demo data when API fails
        function useDemoData() {
            console.log('Using demo data');

            // Clear any existing data
            App.markers.forEach(marker => {
                if (marker.marker) {
                    App.markerCluster.removeLayer(marker.marker);
                }
            });

            Object.values(App.polygons).forEach(item => {
                if (item.polygon) {
                    App.map.removeLayer(item.polygon);
                }
            });

            App.markers = [];
            App.polygons = {};

            // Generate demo data based on the exact format from API sample
            const demoData = [{
                    id: 'demo-1',
                    district_name: "Юнусабадский",
                    neighborhood_name: "Кашгар (0,12 га) - Demo",
                    lat: 41.3187611,
                    lng: 69.2739611,
                    area_hectare: 0.12,
                    total_building_area: 400,
                    residential_area: 400,
                    non_residential_area: 0,
                    adjacent_area: 800,
                    object_information: null,
                    umn_coefficient: "7",
                    qmn_percentage: "11",
                    designated_floors: "520-14-0-Q/24 \n01.08.2024",
                    proposed_floors: "кадастр акт",
                    decision_number: "1",
                    cadastre_certificate: "Dream Visualization",
                    area_strategy: "заключение инвест договора",
                    investor: "0",
                    status: "9",
                    population: null,
                    household_count: null,
                    additional_information: null,
                    main_image: "https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png",
                    polygons: [{
                            start_lat: "41°19'7.54\"С",
                            start_lon: "69°16'26.26\"В",
                            end_lat: "41°19'7.18\"С",
                            end_lon: "69°16'28.05\"В"
                        },
                        {
                            start_lat: "41°19'7.18\"С",
                            start_lon: "69°16'28.05\"В",
                            end_lat: "41°19'8.02\"С",
                            end_lon: "69°16'28.35\"В"
                        },
                        {
                            start_lat: "41°19'8.02\"С",
                            start_lon: "69°16'28.35\"В",
                            end_lat: "41°19'8.40\"С",
                            end_lon: "69°16'26.60\"В"
                        },
                        {
                            start_lat: "41°19'8.40\"С",
                            start_lon: "69°16'26.60\"В",
                            end_lat: "41°19'7.54\"С",
                            end_lon: "69°16'26.26\"В"
                        }
                    ],
                    documents: [{
                        id: 1,
                        doc_type: "pdf-document",
                        path: "assets/data/demo.pdf",
                        url: "#",
                        filename: "Demo Document 1.pdf"
                    }]
                },
                {
                    id: 'demo-2',
                    district_name: "Чиланзарский",
                    neighborhood_name: "Катта Козиробод-1 (3,77/1,52 га) - Demo",
                    lat: 41.2709194,
                    lng: 69.2128778,
                    area_hectare: 18994,
                    total_building_area: 9100,
                    residential_area: 6400,
                    non_residential_area: 2700,
                    adjacent_area: 6100,
                    object_information: null,
                    umn_coefficient: "7",
                    qmn_percentage: "42552",
                    designated_floors: "523-14-0-Q/24\n01.08.2024",
                    proposed_floors: "кадастр акт",
                    decision_number: "1",
                    cadastre_certificate: "Isaar Development",
                    area_strategy: "заключение инвест договора",
                    investor: null,
                    status: "2",
                    population: null,
                    household_count: null,
                    additional_information: null,
                    main_image: "https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png",
                    polygons: [{
                            start_lat: "41°16'15.31\"С",
                            start_lon: "69°12'46.36\"В",
                            end_lat: "41°16'20.51\"С",
                            end_lon: "69°12'48.70\"В"
                        },
                        {
                            start_lat: "41°16'20.51\"С",
                            start_lon: "69°12'48.70\"В",
                            end_lat: "41°16'26.59\"С",
                            end_lon: "69°12'55.48\"В"
                        },
                        {
                            start_lat: "41°16'26.59\"С",
                            start_lon: "69°12'55.48\"В",
                            end_lat: "41°16'28.57\"С",
                            end_lon: "69°12'50.75\"В"
                        },
                        {
                            start_lat: "41°16'28.57\"С",
                            start_lon: "69°12'50.75\"В",
                            end_lat: "41°16'23.36\"С",
                            end_lon: "69°12'46.76\"В"
                        },
                        {
                            start_lat: "41°16'23.36\"С",
                            start_lon: "69°12'46.76\"В",
                            end_lat: "41°16'16.09\"С",
                            end_lon: "69°12'43.48\"В"
                        },
                        {
                            start_lat: "41°16'16.09\"С",
                            start_lon: "69°12'43.48\"В",
                            end_lat: "41°16'15.31\"С",
                            end_lon: "69°12'46.36\"В"
                        }
                    ]
                },
                {
                    id: 'demo-3',
                    district_name: "Чиланзарский",
                    neighborhood_name: "Катта Козиробод-2 (3,77/2,13 га) - Demo",
                    lat: 41.2729194,
                    lng: 69.2148778,
                    area_hectare: 41306,
                    total_building_area: 9400,
                    residential_area: 9400,
                    non_residential_area: 0,
                    adjacent_area: 11900,
                    object_information: null,
                    umn_coefficient: "7",
                    qmn_percentage: "41974",
                    designated_floors: "523-14-0-Q/24\n01.08.2025",
                    proposed_floors: "кадастр акт",
                    decision_number: "1",
                    cadastre_certificate: "Nur Hayat Classics",
                    area_strategy: "заключение инвест договора",
                    investor: null,
                    status: "1",
                    population: null,
                    household_count: null,
                    additional_information: null,
                    main_image: "https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png"
                }
            ];

            // Process demo data
            demoData.forEach(lot => {
                if (lot.lat && lot.lng) {
                    addMarker(lot);
                }

                if (lot.polygons) {
                    addPolygon(lot);
                }
            });

            // Fit map to all markers
            if (App.markers.length > 0) {
                const group = L.featureGroup(App.markers.map(m => m.marker));
                App.map.fitBounds(group.getBounds(), {
                    padding: [50, 50]
                });
            }

            hideLoading();
            showToast('Using demo data', 'warning');
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
                if (App.markers.length === 0 && Object.keys(App.polygons).length === 0) {
                    console.warn('No data loaded on map');
                    showToast('No map data found. Using demo data.', 'warning');
                    // useDemoData();
                }
            } catch (error) {
                console.error('Initialization error:', error);
                showToast('Error initializing map: ' + error.message + '. Using demo data.', 'error');
                // useDemoData();
            } finally {
                hideLoading();
            }
        }

        // Start the app when DOM is ready
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>

</html>
