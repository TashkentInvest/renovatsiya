<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvestUz Map - Complete Integration</title>
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

        /* Modal styles instead of sidebar */
        .info-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .info-modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            max-width: 600px;
            max-height: 80vh;
            width: 90%;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1E3685;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .modal-close-btn {
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

        .modal-body {
            padding: 20px;
        }

        /* Enhanced popup styles */
        .leaflet-popup-content {
            margin: 12px 15px;
            max-width: 300px;
        }

        .popup-header {
            font-size: 16px;
            font-weight: 600;
            color: #1E3685;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .popup-info {
            margin: 5px 0;
            font-size: 14px;
        }

        .popup-info strong {
            color: #333;
        }

        .popup-buttons {
            margin-top: 10px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .popup-btn {
            padding: 5px 10px;
            background: #1E3685;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .popup-btn:hover {
            background: #152a6c;
        }

        .popup-btn.details {
            background: #0E6245;
        }

        .popup-btn.download {
            background: #F5A623;
        }

        .popup-btn.external {
            background: #FF5722;
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

        .toast.success {
            background: #0E6245;
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

        /* Map style controls */
        .map-style-controls {
            position: absolute;
            top: 80px;
            left: 10px;
            z-index: 1000;
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            padding: 8px;
            min-width: 150px;
        }

        .style-control-title {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 6px;
            text-align: center;
        }

        .style-btn {
            padding: 6px 10px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: block;
            width: 100%;
            margin-bottom: 4px;
            transition: all 0.2s;
            text-align: center;
        }

        .style-btn:last-child {
            margin-bottom: 0;
        }

        .style-btn:hover {
            background: #f4f4f4;
        }

        .style-btn.active {
            background: #1E3685;
            color: white;
            border-color: #1E3685;
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
            background: #FFD700;
            color: #333;
            border-color: #FFD700;
        }

        .map-control-btn.auction-active {
            background: #FF5722;
            color: white;
            border-color: #FF5722;
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
            background: rgba(0, 0, 0, 0.3);
            color: white;
        }

        .map-control-btn.auction-active .count-badge {
            background: rgba(255, 255, 255, 0.3);
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

        /* Auction popup */
        .auction-popup {
            padding: 5px;
        }

        .auction-image {
            margin: 8px 0;
            border-radius: 4px;
            overflow: hidden;
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

    <!-- Info Modal -->
    <div id="info-modal" class="info-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Маълумотлар</h2>
                <button class="modal-close-btn" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- KMZ Support -->
    <script src="https://unpkg.com/jszip@3.10.1/dist/jszip.min.js"></script>
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
            currentModal: null,
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
                kmz: 0,
                dopKmz: 0
            },
            mapLayers: {
                osm: null,
                satellite: null,
                hybridBase: null,
                hybridLabels: null,
                currentLayer: 'hybrid'
            },
            apiBaseUrl: (function() {
                const hostname = window.location.hostname;
                const port = window.location.port;
                const protocol = window.location.protocol;

                if (hostname === 'localhost' || hostname === '127.0.0.1') {
                    return `${protocol}//${hostname}${port ? ':' + port : ''}`;
                } else {
                    return `${protocol}//${hostname}${port ? ':' + port : ''}`;
                }
            })()
        };

        // KMZ file list for DOP_DATA
        const DOP_KMZ_FILES = [
            'ALL_RENOVATION_AREA_368_303_230525.kmz',
            'SER-10_Кумарик_1,92 га.kmz',
            'YAK-11_Ракатбоши-6_0,63 га.kmz',
            'YASH-44_Бойкурган-4_1,75 га.kmz'
        ];

         // Enhanced coordinate extraction from Google Maps and Yandex URLs
        function extractCoordinatesFromUrl(url) {
            if (!url) return null;

            try {
                const decodedUrl = decodeURIComponent(url);
                console.log('Processing URL:', decodedUrl);

                // Handle Yandex Maps URLs
                if (decodedUrl.includes('yandex.uz/maps') || decodedUrl.includes('yandex.com/maps')) {
                    const llMatch = decodedUrl.match(/ll=([^&]+)/);
                    if (llMatch) {
                        const coords = llMatch[1].split(',');
                        if (coords.length === 2) {
                            const lng = parseFloat(coords[0]);
                            const lat = parseFloat(coords[1]);
                            // Validate coordinates for Tashkent area
                            if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                console.log('Extracted Yandex coordinates:', [lat, lng]);
                                return [lat, lng];
                            }
                        }
                    }
                }

                // Handle Google Maps URLs - Multiple patterns

                // Pattern 1: @ symbol pattern (@lat,lng,zoom)
                const atPattern = decodedUrl.match(/@(-?\d+\.?\d*),(-?\d+\.?\d*),?\d*z?/);
                if (atPattern) {
                    const lat = parseFloat(atPattern[1]);
                    const lng = parseFloat(atPattern[2]);
                    if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                        console.log('Extracted @ pattern coordinates:', [lat, lng]);
                        return [lat, lng];
                    }
                }

                // Pattern 2: Direct coordinates in URL (lat,lng)
                const coordPattern = decodedUrl.match(/(-?\d+\.\d{4,}),(-?\d+\.\d{4,})/);
                if (coordPattern) {
                    const lat = parseFloat(coordPattern[1]);
                    const lng = parseFloat(coordPattern[2]);
                    if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                        console.log('Extracted direct coordinates:', [lat, lng]);
                        return [lat, lng];
                    }
                }

                // Pattern 3: !3d and !4d pattern
                const bangPattern = decodedUrl.match(/!3d(-?\d+\.?\d*)!4d(-?\d+\.?\d*)/);
                if (bangPattern) {
                    const lat = parseFloat(bangPattern[1]);
                    const lng = parseFloat(bangPattern[2]);
                    if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                        console.log('Extracted !3d/!4d coordinates:', [lat, lng]);
                        return [lat, lng];
                    }
                }

                // Pattern 4: /place/ pattern with coordinates
                const placePattern = decodedUrl.match(/\/place\/[^@]*@(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                if (placePattern) {
                    const lat = parseFloat(placePattern[1]);
                    const lng = parseFloat(placePattern[2]);
                    if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                        console.log('Extracted place pattern coordinates:', [lat, lng]);
                        return [lat, lng];
                    }
                }

                // Pattern 5: Query parameters
                try {
                    const urlObj = new URL(decodedUrl);
                    const params = urlObj.searchParams;

                    const qParam = params.get('q');
                    if (qParam) {
                        const qMatch = qParam.match(/(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                        if (qMatch) {
                            const lat = parseFloat(qMatch[1]);
                            const lng = parseFloat(qMatch[2]);
                            if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                console.log('Extracted q parameter coordinates:', [lat, lng]);
                                return [lat, lng];
                            }
                        }
                    }

                    const llParam = params.get('ll');
                    if (llParam) {
                        const llMatch = llParam.match(/(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                        if (llMatch) {
                            const lng = parseFloat(llMatch[1]);
                            const lat = parseFloat(llMatch[2]);
                            if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                console.log('Extracted ll parameter coordinates:', [lat, lng]);
                                return [lat, lng];
                            }
                        }
                    }
                } catch (urlError) {
                    // URL parsing failed, continue with other methods
                }

                // Pattern 6: Any two decimal numbers that look like coordinates
                const allCoordMatches = decodedUrl.match(/(-?\d+\.\d+)/g);
                if (allCoordMatches && allCoordMatches.length >= 2) {
                    for (let i = 0; i < allCoordMatches.length - 1; i++) {
                        const coord1 = parseFloat(allCoordMatches[i]);
                        const coord2 = parseFloat(allCoordMatches[i + 1]);

                        // Try both orders (lat,lng and lng,lat)
                        if (coord1 >= 39 && coord1 <= 43 && coord2 >= 68 && coord2 <= 71) {
                            console.log('Extracted fallback coordinates (lat,lng):', [coord1, coord2]);
                            return [coord1, coord2];
                        }
                        if (coord2 >= 39 && coord2 <= 43 && coord1 >= 68 && coord1 <= 71) {
                            console.log('Extracted fallback coordinates (lng,lat):', [coord2, coord1]);
                            return [coord2, coord1];
                        }
                    }
                }

                console.log('No valid coordinates found in URL');
                return null;

            } catch (error) {
                console.error('Error extracting coordinates from URL:', url, error);
                return null;
            }
        }

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
                let skippedCount = 0;

                for (const item of data) {
                    if (!item || typeof item !== 'object') {
                        skippedCount++;
                        continue;
                    }

                    try {
                        if (addJsonDataMarker(item)) {
                            processedCount++;
                        } else {
                            skippedCount++;
                            console.log(`Skipped item ${item['№']}: No valid coordinates found`);
                        }
                    } catch (error) {
                        console.warn('Error processing item:', item['№'], error);
                        skippedCount++;
                    }

                    // Update UI every 20 items
                    if ((processedCount + skippedCount) % 20 === 0) {
                        await new Promise(resolve => setTimeout(resolve, 10));
                        updateCounts();
                    }
                }

                console.log(`JSON data processing summary:`);
                console.log(`- Total items: ${data.length}`);
                console.log(`- Successfully processed: ${processedCount}`);
                console.log(`- Skipped (no coordinates): ${skippedCount}`);

                updateCounts();

                if (processedCount > 0) {
                    showToast(`Юкланди ${processedCount} та JSON маълумот (${skippedCount} та ўтказилди)`, 'info');
                    return true;
                } else {
                    showToast(`${data.length} та элемент топилди, лекин ҳеч биридан координата олинмади`, 'warning');
                    return false;
                }

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

                if (error.message.includes('CORS') || error.message.includes('Failed to fetch')) {
                    showToast('Аукцион маълумотларни юклашда CORS хатолиги', 'warning');
                } else {
                    showToast('Аукцион маълумотларни юклашда хатолик: ' + error.message, 'warning');
                }
                return false;
            }
        }

        // Parse description data from KML
        function parseDescriptionData(description) {
            if (!description) return {};

            const data = {};

            try {
                const lines = description.split('\n').map(line => line.trim()).filter(line => line);

                for (const line of lines) {
                    const colonMatch = line.match(/^([^:]+):\s*(.+)$/);
                    const dashMatch = line.match(/^([^-]+)-\s*(.+)$/);
                    const equalMatch = line.match(/^([^=]+)=\s*(.+)$/);

                    let key, value;

                    if (colonMatch) {
                        key = colonMatch[1].trim();
                        value = colonMatch[2].trim();
                    } else if (dashMatch) {
                        key = dashMatch[1].trim();
                        value = dashMatch[2].trim();
                    } else if (equalMatch) {
                        key = equalMatch[1].trim();
                        value = equalMatch[2].trim();
                    } else if (line.includes(' ')) {
                        const parts = line.split(' ');
                        if (parts.length >= 2) {
                            key = parts[0];
                            value = parts.slice(1).join(' ');
                        }
                    }

                    if (key && value) {
                        data[key] = value;
                    }
                }

                // Handle specific formats from your example
                if (description.includes('Лот №')) {
                    const lotMatch = description.match(/Лот №\s*([^\n]+)/);
                    if (lotMatch) data['Лот №'] = lotMatch[1].trim();
                }

                if (description.includes('Туман')) {
                    const regionMatch = description.match(/Туман\s*-\s*([^\n]+)/);
                    if (regionMatch) data['Туман'] = regionMatch[1].trim();
                }

                if (description.includes('МФЙ')) {
                    const mfyMatch = description.match(/МФЙ\s*-\s*([^\n]+)/);
                    if (mfyMatch) data['МФЙ'] = mfyMatch[1].trim();
                }

                if (description.includes('Майдони')) {
                    const areaMatch = description.match(/Майдони\s*-\s*([^\n]+)/);
                    if (areaMatch) data['Майдони'] = areaMatch[1].trim();
                }

                if (description.includes('Стратегия')) {
                    const strategyMatch = description.match(/Стратегия:\s*([^\n]+)/);
                    if (strategyMatch) data['Стратегия'] = strategyMatch[1].trim();
                }

                if (description.includes('Қаватлилик')) {
                    const floorsMatch = description.match(/Қаватлилик:\s*([^\n]+)/);
                    if (floorsMatch) data['Қаватлилик'] = floorsMatch[1].trim();
                }

            } catch (error) {
                console.warn('Error parsing description:', error);
            }

            return data;
        }

        // Process single DOP KMZ file (Yellow styling)
        async function processDopKmzFile(fileName) {
            try {
                const kmzUrl = `/assets/data/DOP_DATA/${fileName}`;
                console.log(`Processing DOP KMZ file: ${fileName}`);

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

                // Yellow style for DOP KMZ layers
                const yellowStyle = {
                    color: '#FFA500',
                    weight: 3,
                    opacity: 0.8,
                    fillColor: '#FFD700',
                    fillOpacity: 0.4
                };

                const currentFileName = fileName;
                const fileNameWithoutExt = fileName.replace('.kmz', '');

                const kmzLayer = L.geoJSON(geoJson, {
                    style: yellowStyle,
                    pointToLayer: function(feature, latlng) {
                        return L.marker(latlng, {
                            icon: L.divIcon({
                                html: '<div style="background-color: #FFD700; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #FFA500;"></div>',
                                className: 'custom-marker',
                                iconSize: [16, 16],
                                iconAnchor: [8, 8]
                            })
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        let lotData = {};

                        const fileNameParts = fileNameWithoutExt.split('_');
                        if (fileNameParts.length >= 2) {
                            lotData['Лот №'] = fileNameParts[0];
                            lotData['Номи'] = fileNameParts.slice(1).join('_');
                        }

                        if (feature.properties && feature.properties.description) {
                            const parsedData = parseDescriptionData(feature.properties.description);
                            lotData = { ...lotData, ...parsedData };
                        }

                        if (feature.properties && feature.properties.name) {
                            lotData['Номи'] = feature.properties.name;
                        }

                        let popupContent = `<div>
                            <div class="popup-header" style="color: #FFA500;">${lotData['Номи'] || fileNameWithoutExt}</div>`;

                        if (lotData['Лот №']) {
                            popupContent += `<div class="popup-info"><strong>Лот №:</strong> ${lotData['Лот №']}</div>`;
                        }

                        if (lotData['Туман']) {
                            popupContent += `<div class="popup-info"><strong>Туман:</strong> ${lotData['Туман']}</div>`;
                        }

                        if (lotData['Майдони']) {
                            popupContent += `<div class="popup-info"><strong>Майдони:</strong> ${lotData['Майдони']}</div>`;
                        }

                        if (lotData['Стратегия']) {
                            popupContent += `<div class="popup-info"><strong>Стратегия:</strong> ${lotData['Стратегия']}</div>`;
                        }

                        popupContent += `<div class="popup-buttons">
                            <button class="popup-btn details" onclick="showDopKmzModal('${currentFileName}')">Тафсилотлар</button>
                            <a href="/assets/data/DOP_DATA/${currentFileName}" download class="popup-btn download">Юклаш</a>
                        </div></div>`;

                        layer.bindPopup(popupContent, { maxWidth: 300 });

                        layer.kmzData = lotData;
                        layer.kmzFileName = currentFileName;

                        if (layer.setStyle) {
                            layer.on('mouseover', function() {
                                this.setStyle({
                                    weight: 5,
                                    fillOpacity: 0.6
                                });
                            });

                            layer.on('mouseout', function() {
                                this.setStyle(yellowStyle);
                            });
                        }

                        layer.on('click', function(e) {
                            showDopKmzModal(currentFileName);
                            L.DomEvent.stopPropagation(e);
                        });
                    }
                });

                kmzLayer.fileName = currentFileName;
                kmzLayer.addTo(App.map);

                App.kmzLayers[`dop_${currentFileName}`] = kmzLayer;
                App.counts.dopKmz++;

                console.log(`Successfully loaded DOP KMZ file: ${fileName}`);
                return true;

            } catch (error) {
                console.error(`Error processing DOP KMZ file ${fileName}:`, error);
                showToast(`Хатолик: ${fileName} - ${error.message}`, 'error');
                return false;
            }
        }

        // Process KMZ files from API (Original logic with status-based colors)
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
                    color: 'green',
                    weight: 2,
                    opacity: 0.7,
                    fillColor: 'green',
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
                            showDetailsModal(lot.id);
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

        // Load all DOP KMZ files
        async function loadDopKmzFiles() {
            try {
                console.log('Loading DOP KMZ files...');

                let successCount = 0;
                let errorCount = 0;

                for (const fileName of DOP_KMZ_FILES) {
                    try {
                        const success = await processDopKmzFile(fileName);
                        if (success) {
                            successCount++;
                        } else {
                            errorCount++;
                        }

                        updateCounts();
                        await new Promise(resolve => setTimeout(resolve, 100));

                    } catch (error) {
                        console.error(`Failed to process ${fileName}:`, error);
                        errorCount++;
                    }
                }

                console.log(`DOP KMZ loading completed: ${successCount} успешно, ${errorCount} с ошибками`);

                if (successCount > 0) {
                    showToast(`Юкланди: ${successCount} та DOP KMZ файл`, 'success');
                    return true;
                } else {
                    showToast('Ҳеч қандай DOP KMZ файл юкланмади', 'warning');
                    return false;
                }

            } catch (error) {
                console.error('Error loading DOP KMZ files:', error);
                showToast('DOP KMZ файлларни юклашда хатолик', 'error');
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

        // Update button texts
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
                    'map-control-btn auction-active' : 'map-control-btn';
            }
        }

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

        // Create map style controls
        function createMapStyleControls() {
            const styleControlDiv = document.createElement('div');
            styleControlDiv.className = 'map-style-controls';

            const title = document.createElement('div');
            title.className = 'style-control-title';
            title.textContent = 'Харита турлари';

            const osmBtn = document.createElement('button');
            osmBtn.className = 'style-btn';
            osmBtn.textContent = 'Стандарт';
            osmBtn.onclick = function() { changeMapStyle('osm'); };

            const satelliteBtn = document.createElement('button');
            satelliteBtn.className = 'style-btn';
            satelliteBtn.textContent = 'Сунъий йўлдош';
            satelliteBtn.onclick = function() { changeMapStyle('satellite'); };

            const hybridBtn = document.createElement('button');
            hybridBtn.className = 'style-btn active';
            hybridBtn.textContent = 'Гибрид';
            hybridBtn.onclick = function() { changeMapStyle('hybrid'); };

            styleControlDiv.appendChild(title);
            styleControlDiv.appendChild(osmBtn);
            styleControlDiv.appendChild(satelliteBtn);
            styleControlDiv.appendChild(hybridBtn);

            document.getElementById('map').appendChild(styleControlDiv);
        }

        // Change map style
        function changeMapStyle(styleType) {
            console.log('Changing map style to:', styleType);

            if (App.mapLayers.currentLayer === 'osm' && App.mapLayers.osm) {
                App.map.removeLayer(App.mapLayers.osm);
            } else if (App.mapLayers.currentLayer === 'satellite' && App.mapLayers.satellite) {
                App.map.removeLayer(App.mapLayers.satellite);
            } else if (App.mapLayers.currentLayer === 'hybrid') {
                if (App.mapLayers.hybridBase) App.map.removeLayer(App.mapLayers.hybridBase);
                if (App.mapLayers.hybridLabels) App.map.removeLayer(App.mapLayers.hybridLabels);
            }

            if (styleType === 'osm') {
                App.map.addLayer(App.mapLayers.osm);
            } else if (styleType === 'satellite') {
                App.map.addLayer(App.mapLayers.satellite);
            } else if (styleType === 'hybrid') {
                App.map.addLayer(App.mapLayers.hybridBase);
                App.map.addLayer(App.mapLayers.hybridLabels);
            }

            App.mapLayers.currentLayer = styleType;

            document.querySelectorAll('.style-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            const buttonTexts = {
                'osm': 'Стандарт',
                'satellite': 'Сунъий йўлдош',
                'hybrid': 'Гибрид'
            };

            document.querySelectorAll('.style-btn').forEach(btn => {
                if (btn.textContent === buttonTexts[styleType]) {
                    btn.classList.add('active');
                }
            });

            console.log('Map style changed to:', styleType);
        }

        // Create map controls
        function createMapControls() {
            const controlDiv = document.createElement('div');
            controlDiv.className = 'map-controls';

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

            const dopKmzButton = document.createElement('button');
            dopKmzButton.id = 'toggle-dop-kmz-btn';
            dopKmzButton.className = 'map-control-btn active';
            dopKmzButton.innerHTML = `
                <div class="control-content">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-file-archive"></i>
                        <span>DOP KMZ файллар</span>
                    </div>
                </div>
                <span class="count-badge">0</span>
            `;
            dopKmzButton.addEventListener('click', toggleDopKmzVisibility);

            const statsPanel = document.createElement('div');
            statsPanel.className = 'stats-panel';

            controlDiv.appendChild(regularButton);
            controlDiv.appendChild(jsonButton);
            controlDiv.appendChild(auctionButton);
            controlDiv.appendChild(dopKmzButton);
            controlDiv.appendChild(statsPanel);

            document.getElementById('map').appendChild(controlDiv);

            updateCounts();
        }

        // Toggle DOP KMZ visibility
        function toggleDopKmzVisibility() {
            const visible = Object.keys(App.kmzLayers).some(key =>
                key.startsWith('dop_') && App.map.hasLayer(App.kmzLayers[key])
            );

            Object.keys(App.kmzLayers).forEach(key => {
                if (key.startsWith('dop_')) {
                    if (visible) {
                        App.map.removeLayer(App.kmzLayers[key]);
                    } else {
                        App.map.addLayer(App.kmzLayers[key]);
                    }
                }
            });

            updateDopKmzButtonText();
        }

        function updateDopKmzButtonText() {
            const button = document.getElementById('toggle-dop-kmz-btn');
            if (button) {
                const visible = Object.keys(App.kmzLayers).some(key =>
                    key.startsWith('dop_') && App.map.hasLayer(App.kmzLayers[key])
                );

                button.innerHTML = `
                    <div class="control-content">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-file-archive"></i>
                            <span>${visible ? 'DOP KMZ яшириш' : 'DOP KMZ кўрсатиш'}</span>
                        </div>
                    </div>
                    <span class="count-badge">${App.counts.dopKmz}</span>
                `;

                button.className = visible ? 'map-control-btn active' : 'map-control-btn';
            }
        }

        // Update counts
        function updateCounts() {
            const jsonBtn = document.getElementById('toggle-json-btn');
            const auctionBtn = document.getElementById('toggle-auction-btn');
            const regularBtn = document.getElementById('regular-count-btn');
            const dopKmzBtn = document.getElementById('toggle-dop-kmz-btn');

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

            if (dopKmzBtn) {
                const dopKmzCount = dopKmzBtn.querySelector('.count-badge');
                if (dopKmzCount) {
                    dopKmzCount.textContent = App.counts.dopKmz;
                }
            }

            updateStatsPanel();
        }

        // Update stats panel
        function updateStatsPanel() {
            const statsPanel = document.querySelector('.stats-panel');
            if (statsPanel) {
                const total = App.counts.regular + App.counts.auction + App.counts.jsonData + App.counts.kmz + App.counts.dopKmz;
                statsPanel.innerHTML = `
                    <div class="stats-title">Статистика</div>
                    <div class="stats-grid">
                        <div class="stats-item">
                            <span class="stats-label">DOP KMZ:</span>
                            <span class="stats-value">${App.counts.dopKmz}</span>
                        </div>
                    </div>
                `;
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

        // Add JSON data marker to map
        function addJsonDataMarker(item) {
            const coordinates = extractCoordinatesFromUrl(item['Таклиф_Харита']);

            if (!coordinates) {
                console.log(`No valid coordinates extracted for item ${item['№']}, URL: ${item['Таклиф_Харита']}`);
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
                    <div class="popup-header">${district} - ${item['№']}</div>
                    <div class="popup-info"><strong>Манзил:</strong> ${address}</div>
                    <div class="popup-info"><strong>Майдон:</strong> ${area} га</div>
                    <div class="popup-info"><strong>Қаватлар:</strong> ${floors}</div>
                    <div class="popup-info"><strong>Фаолият:</strong> ${activity}</div>
                    <div class="popup-info"><span class="badge ${status.class}">${status.text}</span></div>
                    <div class="popup-buttons">
                        <button class="popup-btn details" onclick="showJsonItemModal('${itemId}')">Тафсилотлар</button>
                    </div>
                </div>
            `;

            marker.bindPopup(popup);

            marker.on('click', function(e) {
                showJsonItemModal(this.itemId);
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

        // Safe get function
        function safeGet(obj, key, defaultValue = 'N/A') {
            return (obj && obj[key] !== undefined && obj[key] !== null && obj[key] !== '') ? obj[key] : defaultValue;
        }

        // Add marker to map
        function addMarker(lot) {
            if (!lot) {
                return false;
            }

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
                        <div class="popup-header">${name}</div>
                        <div class="popup-info">${district}</div>
                        <div class="popup-info"><strong>Майдон:</strong> ${area} га</div>
                        <div class="popup-info">${statusText}</div>
                        <div class="popup-buttons">
                            <button class="popup-btn details" onclick="showDetailsModal('${lot.id}')">Тафсилотлар</button>
                        </div>
                    </div>
                `;

                marker.bindPopup(popup);

                marker.on('click', function(e) {
                    showDetailsModal(this.lotId);
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
                            const lat = parseFloat(point.start_lat);
                            const lng = parseFloat(point.start_lon);

                            if (isNaN(lat) || isNaN(lng)) {
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
            }

            if (coordinates.length < 3) {
                console.warn('Not enough valid coordinates found in polygon data');
                return null;
            }

            return coordinates;
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
                color: 'green',
                weight: 2,
                opacity: 0.7,
                fillColor: 'green',
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
                showDetailsModal(this.lotId);
                L.DomEvent.stopPropagation(e);
            });

            polygon.addTo(App.map);

            App.polygons[lot.id] = {
                polygon: polygon,
                data: lot
            };

            return true;
        }

        // Initialize map
        function initMap() {
            App.map = L.map('map', {
                center: [41.311, 69.279],
                zoom: 11,
                minZoom: 10,
                maxZoom: 18
            });

            // Initialize map layers
            App.mapLayers.osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            });

            App.mapLayers.satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri, Maxar, Earthstar Geographics, CNES/Airbus DS, USDA FSA, USGS, Aerogrid, IGN, IGP, and the GIS User Community'
            });

            App.mapLayers.hybridBase = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri, Maxar, Earthstar Geographics, CNES/Airbus DS, USDA FSA, USGS, Aerogrid, IGN, IGP, and the GIS User Community'
            });

            App.mapLayers.hybridLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                attribution: ''
            });

            // Set default to hybrid
            App.mapLayers.hybridBase.addTo(App.map);
            App.mapLayers.hybridLabels.addTo(App.map);
            App.mapLayers.currentLayer = 'hybrid';

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

        // Setup event listeners
        function setupEventListeners() {
            // Close modal when clicking outside
            document.getElementById('info-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            App.map.on('click', function() {
                // Map click handler if needed
            });

            window.addEventListener('resize', function() {
                App.map.invalidateSize();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        }

        // Show modal instead of sidebar
        function showModal(title, content) {
            const modal = document.getElementById('info-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');

            modalTitle.textContent = title;
            modalBody.innerHTML = content;
            modal.classList.add('show');
            App.currentModal = modal;
        }

        // Close modal
        function closeModal() {
            const modal = document.getElementById('info-modal');
            modal.classList.remove('show');
            App.currentModal = null;
        }

        // Show details in modal
        function showDetailsModal(lotId) {
            if (App.isAnimating) return;

            let lot = null;
            let markerEntry = null;

            for (let i = 0; i < App.markers.length; i++) {
                if (App.markers[i].data && App.markers[i].data.id === lotId) {
                    markerEntry = App.markers[i];
                    lot = markerEntry.data;
                    break;
                }
            }

            if (!lot && App.polygons[lotId]) {
                lot = App.polygons[lotId].data;
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

            const status = lot.status ? formatStatus(lot.status) : {
                text: "Статус не указан",
                class: "badge-info"
            };

            const name = safeGet(lot, 'neighborhood_name', safeGet(lot, 'name', 'Unnamed'));
            const district = safeGet(lot, 'district_name', safeGet(lot, 'district'));
            const area = safeGet(lot, 'area_hectare', safeGet(lot, 'area'));

            let modalContent = `
                <div class="section-title">Асосий маълумотлар</div>
                <table class="details-table">
                    <tr><td>Туман:</td><td>${district}</td></tr>
                    <tr><td>Майдон:</td><td>${area} га</td></tr>
                    <tr><td>Ҳолати:</td><td><span class="badge ${status.class}">${status.text}</span></td></tr>
                    <tr><td>Инвестор:</td><td>${safeGet(lot, 'investor')}</td></tr>
                    <tr><td>Қарор рақами:</td><td>${safeGet(lot, 'decision_number')}</td></tr>
                </table>

                <div class="section-title">Техник параметрлар</div>
                <table class="details-table">
                    <tr><td>Мавжуд қаватлар:</td><td>${safeGet(lot, 'designated_floors')}</td></tr>
                    <tr><td>Таклиф қилинган қаватлар:</td><td>${safeGet(lot, 'proposed_floors')}</td></tr>
                    <tr><td>УМН коэффициенти:</td><td>${safeGet(lot, 'umn_coefficient')}</td></tr>
                    <tr><td>Умумий майдон:</td><td>${safeGet(lot, 'total_building_area')} м²</td></tr>
                    <tr><td>Турар жой майдони:</td><td>${safeGet(lot, 'residential_area')} м²</td></tr>
                </table>
            `;

            if (lot.documents && lot.documents.length > 0) {
                modalContent += `<div class="section-title">Ҳужжатлар</div>`;

                const pdfDocs = lot.documents.filter(doc => doc.doc_type === 'pdf-document');
                const kmzDocs = lot.documents.filter(doc => doc.doc_type === 'kmz-document');

                if (pdfDocs.length > 0) {
                    modalContent += `<h4>PDF Ҳужжатлар</h4>`;
                    pdfDocs.forEach(doc => {
                        const fileName = doc.filename || 'Ҳужжат';
                        modalContent += `<a href="${doc.url}" target="_blank" class="document-link">
                            <i class="fas fa-file-pdf"></i> ${fileName}
                        </a>`;
                    });
                }

                if (kmzDocs.length > 0) {
                    modalContent += `<h4>KMZ файллар</h4>`;
                    kmzDocs.forEach(doc => {
                        const fileName = doc.filename || 'KMZ файл';
                        modalContent += `<a href="${doc.url}" download class="document-link">
                            <i class="fas fa-map"></i> ${fileName}
                        </a>`;
                    });
                }
            }

            showModal(name, modalContent);
        }

        // Show JSON item details in modal
        function showJsonItemModal(itemId) {
            if (App.isAnimating) return;

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

            const status = getStatusInfo(item);
            const displayName = `${safeGet(item, 'Туман')} - ${safeGet(item, '№')}`;

            let modalContent = `
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
                    <tr><td>Бош режа ҳолати:</td><td>${safeGet(item, 'Бош_режадаги_ҳолати_ва_қавати')}</td></tr>
                    <tr><td>Таклиф қават:</td><td>${safeGet(item, 'Таклиф_қавати_ва_ҳудуд')}</td></tr>
                    <tr><td>Фаолият тури:</td><td>${safeGet(item, 'Таклиф_Фаолият_тури')}</td></tr>
                </table>
            `;

            if (item['Таклиф_Харита']) {
                modalContent += `
                    <div class="section-title">Харита</div>
                    <a href="${item['Таклиф_Харита']}" target="_blank" class="document-link">
                        <i class="fas fa-map"></i> Харитада кўриш
                    </a>
                `;
            }

            showModal(displayName, modalContent);
        }

        // Show DOP KMZ details in modal
        function showDopKmzModal(fileName) {
            if (App.isAnimating) return;

            const kmzLayer = App.kmzLayers[`dop_${fileName}`];
            if (!kmzLayer) {
                console.error(`DOP KMZ layer ${fileName} not found`);
                return;
            }

            let lotData = {};
            kmzLayer.eachLayer(function(layer) {
                if (layer.kmzData) {
                    lotData = layer.kmzData;
                    return false;
                }
            });

            const displayName = lotData['Номи'] || fileName.replace('.kmz', '');

            let modalContent = `
                <div class="section-title">KMZ файл маълумотлари</div>
                <table class="details-table">
                    <tr><td>Файл номи:</td><td>${fileName}</td></tr>
            `;

            Object.keys(lotData).forEach(key => {
                if (key !== 'Номи') {
                    modalContent += `<tr><td>${key}:</td><td>${lotData[key]}</td></tr>`;
                }
            });

            modalContent += `
                </table>

                <div class="section-title">Файл операциялари</div>
                <a href="/assets/data/DOP_DATA/${fileName}" download class="document-link">
                    <i class="fas fa-download"></i> Файлни юклаш
                </a>
            `;

            showModal(displayName, modalContent);
        }

        // Make functions globally available
        window.showDopKmzModal = showDopKmzModal;
        window.showDetailsModal = showDetailsModal;
        window.showJsonItemModal = showJsonItemModal;
        window.closeModal = closeModal;

        // Initialize application
        async function init() {
            showLoading();

            try {
                console.log('Initializing InvestUz Complete Map...');

                initMap();
                createMapStyleControls();
                createMapControls();
                setupEventListeners();

                // Execute all data fetching in parallel
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
                    }),
                    loadDopKmzFiles().catch(error => {
                        console.warn('DOP KMZ loading failed:', error);
                        return false;
                    })
                ];

                const [regularDataResult, auctionDataResult, jsonDataResult, dopKmzResult] = await Promise.allSettled(dataPromises);

                const regularSuccess = regularDataResult.status === 'fulfilled' && regularDataResult.value;
                const auctionSuccess = auctionDataResult.status === 'fulfilled' && auctionDataResult.value;
                const jsonSuccess = jsonDataResult.status === 'fulfilled' && jsonDataResult.value;
                const dopKmzSuccess = dopKmzResult.status === 'fulfilled' && dopKmzResult.value;

                console.log('Loading results:', {
                    regular: regularSuccess,
                    auction: auctionSuccess,
                    json: jsonSuccess,
                    dopKmz: dopKmzSuccess
                });

                const allMarkers = [];

                if (App.markers.length > 0) {
                    allMarkers.push(...App.markers.map(m => m.marker));
                }

                if (App.jsonDataMarkers.length > 0) {
                    allMarkers.push(...App.jsonDataMarkers.map(m => m.marker));
                }

                // Fit map bounds to show all data
                if (allMarkers.length > 0 || Object.keys(App.kmzLayers).length > 0) {
                    const allLayers = [...allMarkers];

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
                } else {
                    App.map.setView([41.311, 69.279], 11);
                }

                updateCounts();

                const totalLoaded = App.counts.regular + App.counts.auction + App.counts.jsonData + App.counts.kmz + App.counts.dopKmz;
                if (totalLoaded > 0) {
                    showToast(`Жами юкланди: ${totalLoaded} та маълумот`, 'info');
                } else {
                    showToast('Ҳеч қандай маълумот юкланмади. Сервер ишлаётганини текширинг.', 'warning');
                }

                console.log(`Final status: Regular: ${App.counts.regular}, KMZ: ${App.counts.kmz}, JSON: ${App.counts.jsonData}, Auction: ${App.counts.auction}, DOP KMZ: ${App.counts.dopKmz}`);

            } catch (error) {
                console.error('Initialization error:', error);
                showToast('Харитани ишга туширишда хатолик: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        // Start the app when DOM is ready
        document.addEventListener('DOMContentLoaded', init);

        // Handle language switching
        document.addEventListener('DOMContentLoaded', function() {
            const langButtons = document.querySelectorAll('.lang-btn');
            langButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    langButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const lang = this.textContent;
                    console.log('Language switched to:', lang);
                });
            });
        });

    </script>
</body>
</html>

