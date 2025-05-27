<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvestUz Map after auth</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        /* Main styles */
        body,
        html {
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            box-shadow: -3px 0 10px rgba(0, 0, 0, 0.1);
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

        .badge-renovation {
            background-color: #f3e5f5;
            color: #8e24aa;
        }

        .badge-investment {
            background-color: #e1f5fe;
            color: #0277bd;
        }

        .badge-auction {
            background-color: #fff3e0;
            color: #f57c00;
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
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

        /* Map controls */
        .map-controls {
            position: absolute;
            top: 80px;
            right: 10px;
            z-index: 1000;
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            padding: 8px;
            min-width: 200px;
        }

        .map-control-btn {
            padding: 8px 12px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            transition: all 0.2s;
            margin-bottom: 4px;
            width: 100%;
        }

        .map-control-btn:last-child {
            margin-bottom: 0;
        }

        .map-control-btn:hover {
            background: #f4f4f4;
        }

        .map-control-btn.active {
            background: #FF5722;
            color: white;
            border-color: #FF5722;
        }

        .map-control-btn i {
            font-size: 16px;
        }

        .count-badge {
            background: #007bff;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
            min-width: 16px;
            text-align: center;
        }

        .map-control-btn.active .count-badge {
            background: rgba(255, 255, 255, 0.3);
        }

        .control-content {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .auction-popup {
            padding: 5px;
        }

        .auction-image {
            margin: 8px 0;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Stats panel */
        .stats-panel {
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            padding: 8px;
            margin-top: 8px;
            border-radius: 4px;
        }

        .stats-title {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 4px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            font-size: 11px;
        }

        .stats-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-label {
            color: #666;
        }

        .stats-value {
            font-weight: 600;
            color: #333;
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

            .map-controls {
                min-width: 180px;
            }
        }
    </style>
</head>

<body>
    <header class="app-header">
        <div class="app-logo">
            <i class="fas fa-map-marked-alt fa-2x"></i>
            <div class="app-title">ИнвестУз - Инвестиция харитаси</div>
        </div>
        <div class="lang-switcher">
            <button class="lang-btn active">УЗ</button>
            <button class="lang-btn">RU</button>
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
            kmzLayers: {},
            auctionMarkers: [],
            auctionMarkersVisible: false,
            markerCluster: null,
            auctionCluster: null,
            jsonDataMarkers: [],
            jsonDataVisible: true,
            jsonDataCluster: null,
            currentSidebar: null,
            isAnimating: false,
            currentItem: null,
            lastView: {
                center: null,
                zoom: null
            },
            cleanup: [],
            counts: {
                regular: 0,
                auction: 0,
                jsonData: 0,
                kmz: 0
            },
            apiBaseUrl: (function() {
                const hostname = window.location.hostname;
                const port = window.location.port;
                const protocol = window.location.protocol;

                // Use the same origin as the current page to avoid CORS issues
                if (hostname === 'localhost' || hostname === '127.0.0.1') {
                    return `${protocol}//${hostname}${port ? ':' + port : ''}`;
                } else {
                    return `${protocol}//${hostname}${port ? ':' + port : ''}`;
                }
            })()
        };

        // Fetch JSON data from local file
        async function fetchJsonData() {
            try {
                console.log('Fetching JSON data from local file...');

                const response = await fetch('/assets/data/443_output.json');

                if (!response.ok) {
                    console.warn('JSON file not found, skipping JSON data loading');
                    return false;
                }

                const data = await response.json();
                console.log('JSON data response:', data);

                if (!Array.isArray(data) || data.length === 0) {
                    console.warn('No valid JSON data found');
                    return false;
                }

                console.log(`Found ${data.length} items in JSON data`);

                let processedCount = 0;

                for (const item of data) {
                    if (!item || typeof item !== 'object') {
                        continue;
                    }

                    try {
                        if (addJsonDataMarker(item)) {
                            processedCount++;
                        }
                    } catch (error) {
                        console.warn('Error processing item:', item['№'], error);
                    }

                    if (processedCount % 10 === 0) {
                        await new Promise(resolve => setTimeout(resolve, 10));
                        updateCounts();
                    }
                }

                console.log(`Processed ${processedCount} JSON data markers`);

                updateCounts();

                if (processedCount > 0) {
                    showToast(`Юкланди ${processedCount} та JSON маълумот`, 'info');
                    return true;
                }

                return false;
            } catch (error) {
                console.error('Error fetching JSON data:', error);
                showToast('JSON маълумотларни юклашда хатолик: ' + error.message, 'warning');
                return false;
            }
        }

        // Fetch data from API with improved error handling
        async function fetchData() {
            try {
                const apiUrl = `${App.apiBaseUrl}/api/aktivs`;
                console.log(`Fetching data from: ${apiUrl}`);

                const response = await fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    // Add credentials if needed for same-origin requests
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`API request failed with status ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('API response:', data);

                let lotsData = [];

                if (data && data.lots && Array.isArray(data.lots)) {
                    lotsData = data.lots;
                    console.log(`Found ${lotsData.length} lots in API response`);
                } else if (data && Array.isArray(data)) {
                    lotsData = data;
                    console.log(`Found ${lotsData.length} lots in array response`);
                } else {
                    console.warn('Unexpected data format:', data);
                }

                if (lotsData.length === 0) {
                    console.warn('No data found in API response');
                    showToast('Нет данных в API ответе', 'warning');
                    return false;
                }

                let processedCount = 0;
                let processedKmzCount = 0;
                let lotsWithoutCoordinates = 0;
                let idCounter = 1;

                const kmzPromises = [];

                lotsData.forEach(lot => {
                    if (!lot || typeof lot !== 'object') {
                        return;
                    }

                    if (!lot.id) {
                        lot.id = 'lot-' + idCounter++;
                    }

                    // Track lots without coordinates
                    if (!lot.lat || !lot.lng || lot.lat === null || lot.lng === null) {
                        lotsWithoutCoordinates++;
                        console.log(`Lot ${lot.id} (${lot.neighborhood_name}) has no coordinates, will try KMZ`);
                    }

                    // Try to add marker if coordinates exist
                    if (lot.lat && lot.lng && lot.lat !== null && lot.lng !== null) {
                        if (addMarker(lot)) {
                            processedCount++;
                        }
                    }

                    // Try to add polygon if polygon data exists
                    if (lot.polygons && lot.polygons.length > 0) {
                        if (addPolygon(lot)) {
                            processedCount++;
                        }
                    }

                    // Process KMZ files - these can provide coordinates even when lat/lng are null
                    if (lot.documents && Array.isArray(lot.documents)) {
                        const kmzDocs = lot.documents.filter(doc =>
                            doc.doc_type === 'kmz-document'
                        );

                        if (kmzDocs.length > 0) {
                            kmzDocs.forEach(kmzDoc => {
                                const promise = processKmzFile(lot, kmzDoc)
                                    .then(success => {
                                        if (success) {
                                            processedKmzCount++;
                                            updateCounts();
                                        }
                                    })
                                    .catch(error => {
                                        console.error(`Error processing KMZ for lot ${lot.id}:`, error);
                                    });

                                kmzPromises.push(promise);
                            });
                        }
                    }
                });

                updateCounts();

                // Wait for all KMZ files to be processed
                await Promise.allSettled(kmzPromises);

                // Show results
                console.log(`Processing summary:`);
                console.log(`- Total lots: ${lotsData.length}`);
                console.log(`- Lots without coordinates: ${lotsWithoutCoordinates}`);
                console.log(`- Processed markers/polygons: ${processedCount}`);
                console.log(`- Processed KMZ files: ${processedKmzCount}`);

                if (processedCount > 0 || processedKmzCount > 0) {
                    showToast(
                        `API: ${processedCount} маркер/полигон, ${processedKmzCount} KMZ файл`,
                        'info'
                    );

                    // Fit bounds to show all markers and KMZ layers
                    const allLayers = [];

                    if (App.markers.length > 0) {
                        allLayers.push(...App.markers.map(m => m.marker));
                    }

                    Object.values(App.kmzLayers).forEach(kmzLayer => {
                        if (kmzLayer.getBounds) {
                            allLayers.push(kmzLayer);
                        }
                    });

                    if (allLayers.length > 0) {
                        const group = L.featureGroup(allLayers);
                        App.map.fitBounds(group.getBounds(), {
                            padding: [50, 50]
                        });
                    }

                    return true;
                } else {
                    console.warn('No valid items were processed from API data');
                    if (lotsWithoutCoordinates > 0) {
                        showToast(`${lotsData.length} та лот топилди, лекин ${lotsWithoutCoordinates} тасида координаталар йўқ`, 'warning');
                    } else {
                        showToast('API маълумотларида ишлатиладиган элементлар топилмади', 'warning');
                    }
                    return false;
                }
            } catch (error) {
                console.error('Error fetching data:', error);

                // Check if it's a CORS error
                if (error.message.includes('CORS') || error.message.includes('Failed to fetch')) {
                    showToast('API маълумотларни юклашда CORS хатолиги. Сервер созламаларини текширинг.', 'warning');
                } else {
                    showToast('API маълумотларни юклашда хатолик: ' + error.message, 'error');
                }
                return false;
            }
        }

        // Fetch and process auction data with better error handling
        async function fetchAuctionData() {
            try {
                const auctionApiUrl = 'https://projects.toshkentinvest.uz/api/markersing';
                console.log(`Fetching auction data from: ${auctionApiUrl}`);

                const response = await fetch(auctionApiUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                    // Use no-cors mode if CORS is an issue
                    mode: 'cors'
                });

                if (!response.ok) {
                    throw new Error(`Auction API request failed with status ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('Auction API response:', data);

                if (!data || !data.lots || !Array.isArray(data.lots) || data.lots.length === 0) {
                    console.warn('No auction data found in API response');
                    return false;
                }

                console.log(`Found ${data.lots.length} auction lots`);

                let processedCount = 0;

                data.lots.forEach(lot => {
                    if (!lot || typeof lot !== 'object' || !lot.lat || !lot.lng) {
                        return;
                    }

                    const auctionId = 'auction-' + (lot.lot_number || lot.id || processedCount);

                    const auctionIcon = L.divIcon({
                        html: `<div class="auction-marker" style="background-color: #FF5722; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>`,
                        className: 'auction-marker-container',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });

                    const marker = L.marker([parseFloat(lot.lat), parseFloat(lot.lng)], { icon: auctionIcon });

                    const price = lot.start_price ? Number(lot.start_price).toLocaleString('uz-UZ') : 'Белгиланмаган';
                    const propertyName = lot.property_name || 'Номсиз объект';
                    const mainImage = lot.main_image || '';
                    const auctionDate = lot.auction_date || 'Белгиланмаган';
                    const region = lot.region || '';
                    const area = lot.area || '';
                    const address = lot.address || '';
                    const landArea = lot.land_area || 'Белгиланмаган';
                    const propertyCategory = lot.property_category || 'Белгиланмаган';
                    const lotStatus = lot.lot_status || 'Белгиланмаган';
                    const lotLink = lot.lot_link || '#';

                    const popup = `
                        <div class="auction-popup">
                            <h3>${propertyName}</h3>
                            ${mainImage ? `<div class="auction-image">
                                <img src="${mainImage}" alt="${propertyName}" style="width:100%; max-height:150px; object-fit:cover;" onerror="this.style.display='none'">
                            </div>` : ''}
                            <p><strong>Бошланғич нархи:</strong> ${price} сўм</p>
                            <p><strong>Аукцион санаси:</strong> ${auctionDate}</p>
                            <p><strong>Жойлашуви:</strong> ${region}${area ? ', ' + area : ''}${address ? ', ' + address : ''}</p>
                            <p><strong>Майдони:</strong> ${landArea} га</p>
                            <p><strong>Тури:</strong> ${propertyCategory}</p>
                            <p><strong>Ҳолати:</strong> ${lotStatus}</p>
                            ${lotLink && lotLink !== '#' ? `<a href="${lotLink}" target="_blank" class="auction-link" style="display:block; text-align:center; background:#FF5722; color:white; padding:8px; text-decoration:none; border-radius:4px; margin-top:10px;">
                                Батафсил маълумот
                            </a>` : ''}
                        </div>
                    `;

                    marker.bindPopup(popup, { maxWidth: 300 });

                    App.auctionMarkers.push({
                        marker: marker,
                        data: lot
                    });

                    App.auctionCluster.addLayer(marker);
                    App.counts.auction++;
                    processedCount++;
                });

                console.log(`Processed ${processedCount} auction markers`);
                updateCounts();

                return processedCount > 0;
            } catch (error) {
                console.error('Error fetching auction data:', error);

                // More specific error handling
                if (error.message.includes('CORS') || error.message.includes('Failed to fetch')) {
                    showToast('Аукцион маълумотларни юклашда CORS хатолиги', 'warning');
                } else {
                    showToast('Аукцион маълумотларни юклашда хатолик: ' + error.message, 'warning');
                }
                return false;
            }
        }

        // Toggle auction markers visibility
        function toggleAuctionMarkers() {
            if (App.auctionMarkersVisible) {
                App.map.removeLayer(App.auctionCluster);
                App.auctionMarkersVisible = false;
            } else {
                App.map.addLayer(App.auctionCluster);
                App.auctionMarkersVisible = true;
            }

            updateAuctionButtonText();
            updateCounts();
        }

        // Toggle JSON data markers visibility
        function toggleJsonDataMarkers() {
            if (App.jsonDataVisible) {
                App.map.removeLayer(App.jsonDataCluster);
                App.jsonDataVisible = false;
            } else {
                App.map.addLayer(App.jsonDataCluster);
                App.jsonDataVisible = true;
            }

            updateJsonDataButtonText();
            updateCounts();
        }

        // Update the auction toggle button text
        function updateAuctionButtonText() {
            const button = document.getElementById('toggle-auction-btn');
            if (button) {
                const content = button.querySelector('.control-content');

                if (content) {
                    content.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-gavel"></i>
                            <span>${App.auctionMarkersVisible ? 'Аукционларни яшириш' : 'Аукционларни кўрсатиш'}</span>
                        </div>
                    `;
                }

                button.className = App.auctionMarkersVisible ?
                    'map-control-btn active' : 'map-control-btn';
            }
        }

        // Update the JSON data toggle button text
        function updateJsonDataButtonText() {
            const button = document.getElementById('toggle-json-btn');
            if (button) {
                const content = button.querySelector('.control-content');

                if (content) {
                    content.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-layer-group"></i>
                            <span>${App.jsonDataVisible ? 'JSON маълумотларни яшириш' : 'JSON маълумотларни кўрсатиш'}</span>
                        </div>
                    `;
                }

                button.className = App.jsonDataVisible ?
                    'map-control-btn active' : 'map-control-btn';
            }
        }

        // Create map controls with counts
        function createMapControls() {
            const controlDiv = document.createElement('div');
            controlDiv.className = 'map-controls';

            // Create regular data info button (non-toggleable)
            const regularButton = document.createElement('div');
            regularButton.id = 'regular-count-btn';
            regularButton.className = 'map-control-btn';
            regularButton.style.cursor = 'default';
            regularButton.innerHTML = `
                <div class="control-content">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-building"></i>
                        <span>API + KMZ маълумотлар</span>
                    </div>
                </div>
                <span class="count-badge">0</span>
            `;

            // Create JSON data toggle button
            const jsonButton = document.createElement('button');
            jsonButton.id = 'toggle-json-btn';
            jsonButton.className = 'map-control-btn active';
            jsonButton.innerHTML = `
                <div class="control-content">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-layer-group"></i>
                        <span>JSON маълумотларни яшириш</span>
                    </div>
                </div>
                <span class="count-badge">0</span>
            `;
            jsonButton.addEventListener('click', toggleJsonDataMarkers);

            // Create auction toggle button
            const auctionButton = document.createElement('button');
            auctionButton.id = 'toggle-auction-btn';
            auctionButton.className = 'map-control-btn';
            auctionButton.innerHTML = `
                <div class="control-content">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-gavel"></i>
                        <span>Аукционларни кўрсатиш</span>
                    </div>
                </div>
                <span class="count-badge">0</span>
            `;
            auctionButton.addEventListener('click', toggleAuctionMarkers);

            // Create stats panel
            const statsPanel = document.createElement('div');
            statsPanel.className = 'stats-panel';

            // Add all elements to control div
            controlDiv.appendChild(regularButton);
            controlDiv.appendChild(jsonButton);
            controlDiv.appendChild(auctionButton);
            controlDiv.appendChild(statsPanel);

            // Add control to the map container
            document.getElementById('map').appendChild(controlDiv);

            // Initial update
            updateCounts();
        }

        // Setup event listeners
        function setupEventListeners() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('details-btn')) {
                    const lotId = e.target.getAttribute('data-lot-id');
                    const itemId = e.target.getAttribute('data-item-id');

                    if (lotId) {
                        showDetails(lotId);
                    } else if (itemId) {
                        showJsonItemDetails(itemId);
                    }
                }
            });

            App.map.on('click', function() {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function() {
                App.map.invalidateSize();
            });
        }

        // Initialize app with improved error handling
        async function init() {
            showLoading();

            try {
                initMap();
                setupEventListeners();
                createMapControls();

                // Execute data fetching with proper error handling
                const dataPromises = [
                    fetchData().catch(error => {
                        console.warn('Regular data fetch failed:', error);
                        return false;
                    }),
                    fetchAuctionData().catch(error => {
                        console.warn('Auction data fetch failed:', error);
                        return false;
                    }),
                    fetchJsonData().catch(error => {
                        console.warn('JSON data fetch failed:', error);
                        return false;
                    })
                ];

                const [regularDataResult, auctionDataResult, jsonDataResult] = await Promise.allSettled(dataPromises);

                // Process results
                const regularSuccess = regularDataResult.status === 'fulfilled' && regularDataResult.value;
                const auctionSuccess = auctionDataResult.status === 'fulfilled' && auctionDataResult.value;
                const jsonSuccess = jsonDataResult.status === 'fulfilled' && jsonDataResult.value;

                if (!regularSuccess && App.markers.length === 0 &&
                    Object.keys(App.polygons).length === 0 &&
                    Object.keys(App.kmzLayers).length === 0) {
                    console.warn('No regular API data loaded on map');
                }

                if (auctionSuccess) {
                    console.log('Auction data loaded successfully');
                } else {
                    console.warn('No auction data loaded');
                }

                if (jsonSuccess) {
                    console.log('JSON data loaded successfully');
                } else {
                    console.warn('No JSON data loaded');
                }

                const allMarkers = [];

                if (App.markers.length > 0) {
                    allMarkers.push(...App.markers.map(m => m.marker));
                }

                if (App.jsonDataMarkers.length > 0) {
                    allMarkers.push(...App.jsonDataMarkers.map(m => m.marker));
                }

                // Fit map bounds to show all markers
                if (allMarkers.length > 0) {
                    const group = L.featureGroup(allMarkers);
                    App.map.fitBounds(group.getBounds(), {
                        padding: [50, 50]
                    });
                } else {
                    // Default to Tashkent center if no markers
                    App.map.setView([41.311, 69.279], 11);
                }

                // Final count update
                updateCounts();

                // Show summary toast
                const totalLoaded = App.counts.regular + App.counts.auction + App.counts.jsonData + App.counts.kmz;
                if (totalLoaded > 0) {
                    showToast(`Жами юкланди: ${totalLoaded} та маълумот`, 'info');
                } else {
                    showToast('Ҳеч қандай маълумот юкланмади. Сервер ишлаётганини текширинг.', 'warning');
                }

                // Log final status
                console.log(`Final status: Regular: ${App.counts.regular}, KMZ: ${App.counts.kmz}, JSON: ${App.counts.jsonData}, Auction: ${App.counts.auction}`);

            } catch (error) {
                console.error('Initialization error:', error);
                showToast('Харитани ишга туширишда хатолик: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        // Start the app when DOM is ready
        document.addEventListener('DOMContentLoaded', init);

        // Enhanced coordinate extraction from Google Maps and Yandex URLs
        function extractCoordinatesFromUrl(url) {
            if (!url) return null;

            try {
                const decodedUrl = decodeURIComponent(url);

                // Handle Yandex Maps URLs
                if (decodedUrl.includes('yandex.uz/maps')) {
                    const llMatch = decodedUrl.match(/ll=([^&]+)/);
                    if (llMatch) {
                        const coords = llMatch[1].split(',');
                        if (coords.length === 2) {
                            const lng = parseFloat(coords[0]);
                            const lat = parseFloat(coords[1]);
                            // Validate coordinates for Tashkent area
                            if (lat >= 40 && lat <= 42 && lng >= 68 && lng <= 70) {
                                return [lat, lng];
                            }
                        }
                    }
                }

                // Handle Google Maps URLs - Try multiple patterns

                // Pattern 1: Direct coordinates in URL
                const coordPattern1 = decodedUrl.match(/(-?\d+\.\d+),(-?\d+\.\d+)/);
                if (coordPattern1) {
                    const lat = parseFloat(coordPattern1[1]);
                    const lng = parseFloat(coordPattern1[2]);
                    if (lat >= 40 && lat <= 42 && lng >= 68 && lng <= 70) {
                        return [lat, lng];
                    }
                }

                // Pattern 2: @ symbol pattern (@lat,lng)
                const atPattern = decodedUrl.match(/@(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                if (atPattern) {
                    const lat = parseFloat(atPattern[1]);
                    const lng = parseFloat(atPattern[2]);
                    if (lat >= 40 && lat <= 42 && lng >= 68 && lng <= 70) {
                        return [lat, lng];
                    }
                }

                // Pattern 3: !3d and !4d pattern
                const bangPattern = decodedUrl.match(/!3d(-?\d+\.?\d*)!4d(-?\d+\.?\d*)/);
                if (bangPattern) {
                    const lat = parseFloat(bangPattern[1]);
                    const lng = parseFloat(bangPattern[2]);
                    if (lat >= 40 && lat <= 42 && lng >= 68 && lng <= 70) {
                        return [lat, lng];
                    }
                }

                // Pattern 4: Query parameters
                try {
                    const urlObj = new URL(decodedUrl);
                    const params = urlObj.searchParams;

                    const qParam = params.get('q');
                    if (qParam) {
                        const qMatch = qParam.match(/(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                        if (qMatch) {
                            const lat = parseFloat(qMatch[1]);
                            const lng = parseFloat(qMatch[2]);
                            if (lat >= 40 && lat <= 42 && lng >= 68 && lng <= 70) {
                                return [lat, lng];
                            }
                        }
                    }

                    const llParam = params.get('ll');
                    if (llParam) {
                        const llMatch = llParam.match(/(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                        if (llMatch) {
                            const lat = parseFloat(llMatch[1]);
                            const lng = parseFloat(llMatch[2]);
                            if (lat >= 40 && lat <= 42 && lng >= 68 && lng <= 70) {
                                return [lat, lng];
                            }
                        }
                    }
                } catch (urlError) {
                    // URL parsing failed, continue with other methods
                }

                // For Google shortened URLs, we'll need to expand them server-side
                // For now, return null and handle gracefully
                return null;

            } catch (error) {
                console.error('Error extracting coordinates from URL:', url, error);
                return null;
            }
        }

        // Get status info for different types
        function getStatusInfo(item) {
            const type = item['Таклиф_тури_(Реновация,_Инвестиция,_Аукцион)'];

            switch (type) {
                case 'Реновация':
                    return { text: 'Реновация', class: 'badge-renovation', color: '#8e24aa' };
                case 'Инвестиция':
                    return { text: 'Инвестиция', class: 'badge-investment', color: '#0277bd' };
                case 'Аукцион':
                    return { text: 'Аукцион', class: 'badge-auction', color: '#f57c00' };
                default:
                    return { text: type || 'Белгисиз', class: 'badge-info', color: '#1E3685' };
            }
        }

        // Create marker icon based on type
        function createMarkerIcon(item) {
            const status = getStatusInfo(item);

            return L.divIcon({
                html: `<div style="background-color: ${status.color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>`,
                className: 'custom-marker',
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
        }

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

        // Update counts in control panel
        function updateCounts() {
            const jsonBtn = document.getElementById('toggle-json-btn');
            const auctionBtn = document.getElementById('toggle-auction-btn');
            const regularBtn = document.getElementById('regular-count-btn');

            if (jsonBtn) {
                const jsonCount = jsonBtn.querySelector('.count-badge');
                if (jsonCount) {
                    jsonCount.textContent = App.counts.jsonData;
                }
            }

            if (auctionBtn) {
                const auctionCount = auctionBtn.querySelector('.count-badge');
                if (auctionCount) {
                    auctionCount.textContent = App.counts.auction;
                }
            }

            if (regularBtn) {
                const regularCount = regularBtn.querySelector('.count-badge');
                if (regularCount) {
                    regularCount.textContent = App.counts.regular + App.counts.kmz;
                }
            }

            // Update stats panel
            updateStatsPanel();
        }

        // Update stats panel
        function updateStatsPanel() {
            const statsPanel = document.querySelector('.stats-panel');
            if (statsPanel) {
                const total = App.counts.regular + App.counts.auction + App.counts.jsonData + App.counts.kmz;
                statsPanel.innerHTML = `
                    <div class="stats-title">Статистика</div>
                    <div class="stats-grid">
                        <div class="stats-item">
                            <span class="stats-label">Жами:</span>
                            <span class="stats-value">${total}</span>
                        </div>
                        <div class="stats-item">
                            <span class="stats-label">API:</span>
                            <span class="stats-value">${App.counts.regular}</span>
                        </div>
                        <div class="stats-item">
                            <span class="stats-label">KMZ:</span>
                            <span class="stats-value">${App.counts.kmz}</span>
                        </div>
                        <div class="stats-item">
                            <span class="stats-label">JSON:</span>
                            <span class="stats-value">${App.counts.jsonData}</span>
                        </div>
                        <div class="stats-item">
                            <span class="stats-label">Аукцион:</span>
                            <span class="stats-value">${App.counts.auction}</span>
                        </div>
                        <div class="stats-item">
                            <span class="stats-label">Кўрсатилган:</span>
                            <span class="stats-value">${(App.jsonDataVisible ? App.counts.jsonData : 0) + (App.auctionMarkersVisible ? App.counts.auction : 0) + App.counts.regular + App.counts.kmz}</span>
                        </div>
                    </div>
                `;
            }
        }

        // Convert DMS coordinates to decimal
        function dmsToDecimal(dmsStr) {
            if (!dmsStr) return null;

            const regex = /(\d+)°(\d+)'(\d+\.\d+)"([СNЮSВEЗW])/;
            const match = dmsStr.match(regex);

            if (!match) return null;

            const degrees = parseFloat(match[1]);
            const minutes = parseFloat(match[2]);
            const seconds = parseFloat(match[3]);
            const direction = match[4];

            let decimal = degrees + (minutes / 60) + (seconds / 3600);

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

        // Safe get function for object properties
        function safeGet(obj, key, defaultValue = 'N/A') {
            return (obj && obj[key] !== undefined && obj[key] !== null && obj[key] !== '') ? obj[key] : defaultValue;
        }

        // Initialize map
        function initMap() {
            App.map = L.map('map', {
                center: [41.311, 69.279],
                zoom: 11,
                minZoom: 10,
                maxZoom: 18
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(App.map);

            App.markerCluster = L.markerClusterGroup({
                chunkedLoading: true,
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                disableClusteringAtZoom: 16
            });

            App.auctionCluster = L.markerClusterGroup({
                chunkedLoading: true,
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                disableClusteringAtZoom: 16
            });

            App.jsonDataCluster = L.markerClusterGroup({
                chunkedLoading: true,
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                disableClusteringAtZoom: 16
            });

            App.map.addLayer(App.markerCluster);
            App.map.addLayer(App.jsonDataCluster);
        }

        // Add JSON data marker to map
        function addJsonDataMarker(item) {
            const coordinates = extractCoordinatesFromUrl(item['Таклиф_Харита']);

            if (!coordinates) {
                return false;
            }

            const itemId = 'json-item-' + item['№'];
            const icon = createMarkerIcon(item);
            const marker = L.marker(coordinates, { icon: icon });

            marker.itemId = itemId;

            const status = getStatusInfo(item);
            const district = safeGet(item, 'Туман');
            const address = safeGet(item, 'Манзил_(МФЙ,_кўча)');
            const area = safeGet(item, 'Таклиф_Ер_майдони_(га)');
            const floors = safeGet(item, 'Таклиф_қавати_ва_ҳудуд');
            const activity = safeGet(item, 'Таклиф_Фаолият_тури');

            const popup = `
                <div>
                    <h3>${district} - ${item['№']}</h3>
                    <p><strong>Манзил:</strong> ${address}</p>
                    <p><strong>Майдон:</strong> ${area} га</p>
                    <p><strong>Қаватлар:</strong> ${floors}</p>
                    <p><strong>Фаолият:</strong> ${activity}</p>
                    <p><span class="badge ${status.class}">${status.text}</span></p>
                    <button class="details-btn" data-item-id="${itemId}">Тафсилотлар</button>
                </div>
            `;

            marker.bindPopup(popup);

            marker.on('click', function(e) {
                showJsonItemDetails(this.itemId);
                L.DomEvent.stopPropagation(e);
            });

            App.jsonDataCluster.addLayer(marker);
            App.jsonDataMarkers.push({
                marker: marker,
                data: item
            });

            App.counts.jsonData++;
            return true;
        }

        // Show JSON item details
        function showJsonItemDetails(itemId) {
            if (App.isAnimating) {
                return;
            }

            let item = null;
            for (let i = 0; i < App.jsonDataMarkers.length; i++) {
                if (App.jsonDataMarkers[i].marker.itemId === itemId) {
                    item = App.jsonDataMarkers[i].data;
                    break;
                }
            }

            if (!item) {
                console.error(`Item with ID ${itemId} not found`);
                return;
            }

            App.lastView.zoom = App.map.getZoom();
            App.lastView.center = App.map.getCenter();
            closeSidebar(true);

            App.currentItem = itemId;
            App.isAnimating = true;

            const status = getStatusInfo(item);
            const sidebar = document.createElement('div');
            sidebar.className = 'sidebar';
            sidebar.id = `sidebar-${Date.now()}`;

            let sidebarHtml = `
                <div class="sidebar-header">
                    <h2>${safeGet(item, 'Туман')} - ${safeGet(item, '№')}</h2>
                    <button class="sidebar-close-btn">×</button>
                </div>
                <div class="sidebar-content">
                    <div class="section-title">Асосий маълумотлар</div>
                    <table class="details-table">
                        <tr><td>№:</td><td>${safeGet(item, '№')}</td></tr>
                        <tr><td>Туман:</td><td>${safeGet(item, 'Туман')}</td></tr>
                        <tr><td>Манзил:</td><td>${safeGet(item, 'Манзил_(МФЙ,_кўча)')}</td></tr>
                        <tr><td>Тури:</td><td><span class="badge ${status.class}">${status.text}</span></td></tr>
                        <tr><td>Майдон:</td><td>${safeGet(item, 'Таклиф_Ер_майдони_(га)')} га</td></tr>
                    </table>

                    <div class="section-title">Лойиҳа тафсилотлари</div>
                    <table class="details-table">
                        <tr><td>Бош режадаги ҳолат:</td><td>${safeGet(item, 'Бош_режадаги_ҳолати_ва_қавати')}</td></tr>
                        <tr><td>Таклиф қават:</td><td>${safeGet(item, 'Таклиф_қавати_ва_ҳудуд')}</td></tr>
                        <tr><td>Фаолият тури:</td><td>${safeGet(item, 'Таклиф_Фаолият_тури')}</td></tr>
                        <tr><td>Филтр фаолияти:</td><td>${safeGet(item, 'Таклиф_Фалияти_филтир_учун')}</td></tr>
                    </table>

                    <div class="section-title">Бош режа ҳолати</div>
                    <table class="details-table">
                        <tr><td>Киритилганлиги:</td><td>${safeGet(item, 'Таклиф_Бош_режага_таклиф_киритилганлиги_(таклиф_берилган_лекин_киритилмаган_бўлас_КИРИТИЛМАГАН_хисобланади)')}</td></tr>
                    </table>
            `;

            // Add technical parameters if available
            if (item['Этажность'] || item['Қурилиш_ости_майдони'] || item['Бинонинг_умумий_майдони']) {
                sidebarHtml += `
                    <div class="section-title">Техник параметрлар</div>
                    <table class="details-table">
                        ${item['Этажность'] ? `<tr><td>Этажность:</td><td>${item['Этажность']}</td></tr>` : ''}
                        ${item['Қурилиш_ости_майдони'] ? `<tr><td>Қурилиш ости майдони:</td><td>${item['Қурилиш_ости_майдони']} м²</td></tr>` : ''}
                        ${item['Бинонинг_умумий_майдони'] ? `<tr><td>Умумий майдон:</td><td>${item['Бинонинг_умумий_майдони']} м²</td></tr>` : ''}
                        ${item['Хизмат_кўрсатиш_сохаси'] ? `<tr><td>Хизмат кўрсатиш:</td><td>${item['Хизмат_кўрсатиш_сохаси']} м²</td></tr>` : ''}
                        ${item['Яшаш_майдон'] ? `<tr><td>Яшаш майдон:</td><td>${item['Яшаш_майдон']} м²</td></tr>` : ''}
                    </table>
                `;
            }

            // Add demographic information if available
            if (item['хонадонлар_сони'] || item['Ахоли_сони']) {
                sidebarHtml += `
                    <div class="section-title">Демографик маълумотлар</div>
                    <table class="details-table">
                        ${item['хонадонлар_сони'] ? `<tr><td>Хонадонлар сони:</td><td>${item['хонадонлар_сони']}</td></tr>` : ''}
                        ${item['Ахоли_сони'] ? `<tr><td>Аҳоли сони:</td><td>${item['Ахоли_сони']}</td></tr>` : ''}
                    </table>
                `;
            }

            // Add map link if available
            if (item['Таклиф_Харита']) {
                sidebarHtml += `
                    <div class="section-title">Харита</div>
                    <a href="${item['Таклиф_Харита']}" target="_blank" class="document-link">
                        <i class="fas fa-map"></i> Харитада кўриш
                    </a>
                `;
            }

            sidebarHtml += `</div>`;

            sidebar.innerHTML = sidebarHtml;
            document.body.appendChild(sidebar);
            App.currentSidebar = sidebar;

            const closeBtn = sidebar.querySelector('.sidebar-close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeSidebar();
                });
            }

            requestAnimationFrame(() => {
                sidebar.classList.add('open');

                setTimeout(() => {
                    const markerEntry = App.jsonDataMarkers.find(m => m.marker.itemId === itemId);
                    if (markerEntry) {
                        const coordinates = markerEntry.marker.getLatLng();
                        App.map.setView(coordinates, 17, { animate: true });
                    }

                    App.isAnimating = false;
                }, 300);
            });

            showToast('Маълумотлар юкланди');
        }

        // Extract polygon coordinates
        function extractPolygonCoordinates(polygonData) {
            if (!polygonData) {
                return null;
            }

            let coordinates = [];

            if (Array.isArray(polygonData)) {
                if (polygonData.length < 3) {
                    return null;
                }

                if (typeof polygonData[0] === 'object') {
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
                    else if (polygonData[0].lat && polygonData[0].lng) {
                        coordinates = polygonData.map(point => {
                            return [parseFloat(point.lat), parseFloat(point.lng)];
                        });
                    }
                    else if (polygonData[0].latitude && polygonData[0].longitude) {
                        coordinates = polygonData.map(point => {
                            return [parseFloat(point.latitude), parseFloat(point.longitude)];
                        });
                    }
                    else if (Array.isArray(polygonData[0]) && polygonData[0].length === 2) {
                        coordinates = polygonData.map(point => {
                            return [parseFloat(point[0]), parseFloat(point[1])];
                        });
                    }
                }
                else if (typeof polygonData[0] === 'number' && polygonData.length >= 6 && polygonData.length % 2 === 0) {
                    for (let i = 0; i < polygonData.length; i += 2) {
                        coordinates.push([parseFloat(polygonData[i]), parseFloat(polygonData[i + 1])]);
                    }
                }
            }
            else if (polygonData.type === 'Polygon' && Array.isArray(polygonData.coordinates)) {
                if (polygonData.coordinates.length > 0 && Array.isArray(polygonData.coordinates[0])) {
                    coordinates = polygonData.coordinates[0].map(coord => {
                        return [parseFloat(coord[1]), parseFloat(coord[0])];
                    });
                }
            }

            if (coordinates.length < 3) {
                console.warn('Not enough valid coordinates found in polygon data');
                return null;
            }

            return coordinates;
        }

        // Add marker to map - improved to handle missing coordinates
        function addMarker(lot) {
            if (!lot) {
                return false;
            }

            // Only create point markers if we have coordinates
            if (lot.lat && lot.lng && lot.lat !== null && lot.lng !== null) {
                const marker = L.marker([parseFloat(lot.lat), parseFloat(lot.lng)]);

                if (!lot.id) {
                    lot.id = 'lot-' + Math.random().toString(36).substr(2, 9);
                }

                marker.lotId = lot.id;

                const name = safeGet(lot, 'neighborhood_name', safeGet(lot, 'name', 'Unnamed'));
                const district = safeGet(lot, 'district_name', safeGet(lot, 'district'));
                const area = safeGet(lot, 'area_hectare', safeGet(lot, 'area'));
                const statusText = lot.status ? formatStatus(lot.status).text : 'Статус не указан';

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

                marker.on('click', function(e) {
                    showDetails(this.lotId);
                    L.DomEvent.stopPropagation(e);
                });

                App.markerCluster.addLayer(marker);

                App.markers.push({
                    marker: marker,
                    data: lot
                });

                App.counts.regular++;
                return true;
            }

            return false;
        }

        // Add polygon to map
        function addPolygon(lot) {
            if (!lot || !lot.id || !lot.polygons) {
                return false;
            }

            const coords = extractPolygonCoordinates(lot.polygons);
            if (!coords) {
                return false;
            }

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

            const polygon = L.polygon(coords, style);
            polygon.lotId = lot.id;

            polygon.on('mouseover', function() {
                this.setStyle({
                    weight: 3,
                    fillOpacity: 0.4
                });
            });

            polygon.on('mouseout', function() {
                this.setStyle(style);
            });

            polygon.on('click', function(e) {
                showDetails(this.lotId);
                L.DomEvent.stopPropagation(e);
            });

            polygon.addTo(App.map);

            App.polygons[lot.id] = {
                polygon: polygon,
                data: lot
            };

            return true;
        }

        // Process KMZ files
        async function processKmzFile(lot, kmzDoc) {
            if (!lot || !lot.id || !kmzDoc) {
                console.error('Invalid lot or KMZ document data');
                return false;
            }

            try {
                let kmzUrl = kmzDoc.url;

                if (kmzUrl.startsWith('http') && !kmzUrl.includes(window.location.hostname)) {
                    const paths = kmzUrl.split('/assets/');
                    if (paths.length > 1) {
                        kmzUrl = App.apiBaseUrl + '/assets/data/BASA_RENOVA/' + paths[1].split('/').pop();
                    }
                }

                console.log(`Processing KMZ file: ${kmzUrl} for lot ID: ${lot.id}`);

                if (App.kmzLayers[kmzUrl]) {
                    console.log('KMZ layer already exists, adding to lot');
                    App.kmzLayers[kmzUrl].lotId = lot.id;
                    App.kmzLayers[kmzUrl].lotData = lot;
                    return true;
                }

                const response = await fetch(kmzUrl);
                if (!response.ok) {
                    throw new Error(`Failed to fetch KMZ file: ${response.statusText}`);
                }

                const kmzData = await response.arrayBuffer();
                const zip = await JSZip.loadAsync(kmzData);

                let kmlFile;
                let kmlContent;

                if (zip.file('doc.kml')) {
                    kmlFile = zip.file('doc.kml');
                } else {
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

                kmlContent = await kmlFile.async('text');

                const parser = new DOMParser();
                const kmlDoc = parser.parseFromString(kmlContent, 'text/xml');
                const geoJson = toGeoJSON.kml(kmlDoc);

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

                const kmzLayer = L.geoJSON(geoJson, {
                    style: style,
                    pointToLayer: function(feature, latlng) {
                        return L.marker(latlng);
                    },
                    onEachFeature: function(feature, layer) {
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

                        if (feature.properties && feature.properties.name) {
                            let popupContent = `<div><strong>${feature.properties.name}</strong>`;

                            if (feature.properties.description) {
                                popupContent += `<p>${feature.properties.description}</p>`;
                            }

                            popupContent += `<button class="details-btn" data-lot-id="${lot.id}">Тафсилотлар</button></div>`;

                            layer.bindPopup(popupContent);
                        }

                        layer.on('click', function(e) {
                            showDetails(lot.id);
                            L.DomEvent.stopPropagation(e);
                        });
                    }
                });

                kmzLayer.lotId = lot.id;
                kmzLayer.lotData = lot;

                kmzLayer.addTo(App.map);

                App.kmzLayers[kmzUrl] = kmzLayer;
                App.counts.kmz++;

                console.log(`Successfully processed KMZ file for lot ${lot.id}`);
                return true;

            } catch (error) {
                console.error(`Error processing KMZ file for lot ${lot.id}: ${error.message}`, error);
                return false;
            }
        }

        // Show details function - improved to handle API data structure
        function showDetails(lotId) {
            if (!lotId || App.isAnimating) {
                return;
            }

            let lot = null;
            let markerEntry = null;
            let polygonEntry = null;

            // Find lot data
            for (let i = 0; i < App.markers.length; i++) {
                if (App.markers[i].data && App.markers[i].data.id === lotId) {
                    markerEntry = App.markers[i];
                    lot = markerEntry.data;
                    break;
                }
            }

            if (!lot && App.polygons[lotId]) {
                polygonEntry = App.polygons[lotId];
                lot = polygonEntry.data;
            }

            if (!lot) {
                for (const url in App.kmzLayers) {
                    const kmzLayer = App.kmzLayers[url];
                    if (kmzLayer.lotId === lotId) {
                        if (kmzLayer.lotData) {
                            lot = kmzLayer.lotData;
                            break;
                        }
                    }
                }
            }

            if (!lot) {
                console.error(`Lot with ID ${lotId} not found`);
                return;
            }

            App.lastView.zoom = App.map.getZoom();
            App.lastView.center = App.map.getCenter();
            closeSidebar(true);

            App.currentItem = lotId;
            App.isAnimating = true;

            const status = lot.status ? formatStatus(lot.status) : {
                text: "Статус не указан",
                class: "badge-info"
            };

            // Use API data structure field names
            const name = safeGet(lot, 'neighborhood_name', safeGet(lot, 'name', 'Unnamed'));
            const district = safeGet(lot, 'district_name', safeGet(lot, 'district'));
            const area = safeGet(lot, 'area_hectare', safeGet(lot, 'area'));

            // API specific fields
            const totalBuildingArea = safeGet(lot, 'total_building_area');
            const residentialArea = safeGet(lot, 'residential_area');
            const nonResidentialArea = safeGet(lot, 'non_residential_area');
            const umnCoefficient = safeGet(lot, 'umn_coefficient');
            const qmnPercentage = safeGet(lot, 'qmn_percentage');
            const designatedFloors = safeGet(lot, 'designated_floors');
            const proposedFloors = safeGet(lot, 'proposed_floors');
            const decisionNumber = safeGet(lot, 'decision_number');
            const cadastreCertificate = safeGet(lot, 'cadastre_certificate');
            const areaStrategy = safeGet(lot, 'area_strategy');
            const investor = safeGet(lot, 'investor');
            const population = safeGet(lot, 'population');
            const householdCount = safeGet(lot, 'household_count');
            const additionalInformation = safeGet(lot, 'additional_information');

            // Create sidebar
            const sidebar = document.createElement('div');
            sidebar.className = 'sidebar';
            sidebar.id = `sidebar-${Date.now()}`;

            // Generate sidebar HTML with API data structure
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
                        <tr><td>Инвестор:</td><td>${investor}</td></tr>
                        <tr><td>Қарор рақами:</td><td>${decisionNumber}</td></tr>
                        <tr><td>Стратегия муддати:</td><td>${areaStrategy}</td></tr>
                    </table>

                    <div class="section-title">Техник параметрлар</div>
                    <table class="details-table">
                        <tr><td>Кадастр ҳужжати:</td><td>${cadastreCertificate}</td></tr>
                        <tr><td>Мавжуд қаватлар:</td><td>${designatedFloors}</td></tr>
                        <tr><td>Таклиф қилинган қаватлар:</td><td>${proposedFloors}</td></tr>
                        <tr><td>УМН коэффициенти:</td><td>${umnCoefficient}</td></tr>
                        <tr><td>ҚМН фоизи:</td><td>${qmnPercentage}%</td></tr>
                    </table>

                    <div class="section-title">Қурилиш майдонлари</div>
                    <table class="details-table">
                        <tr><td>Умумий майдон:</td><td>${totalBuildingArea} м²</td></tr>
                        <tr><td>Турар жой майдони:</td><td>${residentialArea} м²</td></tr>
                        <tr><td>Нотурар жой майдони:</td><td>${nonResidentialArea || '0'} м²</td></tr>
                    </table>`;

            // Add demographic information if available
            if (population !== 'N/A' || householdCount !== 'N/A') {
                sidebarHtml += `
                    <div class="section-title">Демографик маълумотлар</div>
                    <table class="details-table">
                        ${population !== 'N/A' ? `<tr><td>Аҳоли сони:</td><td>${population}</td></tr>` : ''}
                        ${householdCount !== 'N/A' ? `<tr><td>Хонадонлар сони:</td><td>${householdCount}</td></tr>` : ''}
                    </table>
                `;
            }

            // Add additional information if available
            if (additionalInformation !== 'N/A') {
                sidebarHtml += `
                    <div class="section-title">Қўшимча маълумотлар</div>
                    <div class="additional-info">${additionalInformation}</div>
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

                // Add PDF documents
                if (pdfDocs.length > 0) {
                    sidebarHtml += `<div class="doc-group">
                        <h4>PDF Ҳужжатлар</h4>`;

                    pdfDocs.forEach(doc => {
                        const fileName = doc.filename || 'Ҳужжат';
                        const pdfUrl = doc.url;

                        sidebarHtml += `
                            <a href="${pdfUrl}" target="_blank" class="document-link">
                                <i class="fas fa-file-pdf"></i> ${fileName}
                            </a>`;
                    });

                    sidebarHtml += `</div>`;
                }

                // Add KMZ documents
                if (kmzDocs.length > 0) {
                    sidebarHtml += `<div class="doc-group">
                        <h4>KMZ Харита файллари</h4>`;

                    kmzDocs.forEach(doc => {
                        const fileName = doc.filename || 'KMZ файл';
                        const kmzUrl = doc.url;

                        sidebarHtml += `
                            <a href="${kmzUrl}" download class="document-link">
                                <i class="fas fa-map"></i> ${fileName}
                            </a>`;
                    });

                    sidebarHtml += `</div>`;
                }

                sidebarHtml += `</div>`;
            }

            sidebarHtml += `</div>`;

            sidebar.innerHTML = sidebarHtml;
            document.body.appendChild(sidebar);
            App.currentSidebar = sidebar;

            const closeBtn = sidebar.querySelector('.sidebar-close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeSidebar();
                });
            }

            requestAnimationFrame(() => {
                sidebar.classList.add('open');
                setTimeout(() => {
                    App.isAnimating = false;
                }, 300);
            });

        //     showToast('Маълумотлар юкланди');
        // }оли сони:</td><td>${population}</td></tr>` : ''}
        //                 ${household !== 'N/A' ? `<tr><td>Хонадонлар сони:</td><td>${household}</td></tr>` : ''}
        //             </table>
        //         `;
        //     }

            // Add documents if available
            if (lot.documents && lot.documents.length > 0) {
                sidebarHtml += `
                    <div class="section-title">Ҳужжатлар</div>
                    <div class="documents-list">`;

                // Group documents by type
                const pdfDocs = lot.documents.filter(doc => doc.doc_type === 'pdf-document');
                const kmzDocs = lot.documents.filter(doc => doc.doc_type === 'kmz-document');

                // Add PDF documents
                if (pdfDocs.length > 0) {
                    sidebarHtml += `<div class="doc-group">
                        <h4>PDF Ҳужжатлар</h4>`;

                    pdfDocs.forEach(doc => {
                        const fileName = doc.filename || 'Ҳужжат';
                        // Fix URL to use the apiBaseUrl
                        let pdfUrl = doc.url;
                        if (pdfUrl.startsWith('http') && !pdfUrl.includes(window.location.hostname)) {
                            const paths = pdfUrl.split('/assets/');
                            if (paths.length > 1) {
                                pdfUrl = App.apiBaseUrl + '/assets/data/RENOVATSIYA ISXOD PDF/' + paths[1].split(
                                    '/').pop();
                            }
                        }

                        sidebarHtml += `
                            <a href="${pdfUrl}" target="_blank" class="document-link">
                                <i class="fas fa-file-pdf"></i> ${fileName}
                            </a>`;
                    });

                    sidebarHtml += `</div>`;
                }

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

            sidebarHtml += `</div>`;

            sidebar.innerHTML = sidebarHtml;
            document.body.appendChild(sidebar);
            App.currentSidebar = sidebar;

            // const closeBtn = sidebar.querySelector('.sidebar-close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeSidebar();
                });
            }

            requestAnimationFrame(() => {
                sidebar.classList.add('open');
                setTimeout(() => {
                    App.isAnimating = false;
                }, 300);
            });

            showToast('Маълумотлар юкланди');
        }

        // Close sidebar
        function closeSidebar(immediate = false) {
            if (!App.currentSidebar) return;

            if (immediate) {
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

                App.currentSidebar.remove();
                App.currentSidebar = null;
                App.currentItem = null;
                return;
            }

            App.currentSidebar.classList.remove('open');

            setTimeout(() => {
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

                App.currentSidebar.remove();
                App.currentSidebar = null;
                App.currentItem = null;

                if (App.lastView.center && App.lastView.zoom) {
                    App.map.setView(App.lastView.center, App.lastView.zoom, {
                        animate: true
                    });
                }
            }, 300);
        }
    </script>
</body>

</html>
