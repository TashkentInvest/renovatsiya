<script>
    const baseUrl = "{{ url(‘‘) }}";
        let usdRate = null;

        document.addEventListener(‘DOMContentLoaded’, function() {
            const script = document.createElement(‘script’);
            script.src =
                `https://maps.googleapis.com/maps/api/js?key=AIzaSyAAnUwWTguBMsDU8UrQ7Re-caVeYCmcHQY&libraries&callback=initMap`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        });


        function initMap() {
            const urlParams = new URLSearchParams(window.location.search);
            const urlLat = parseFloat(urlParams.get(‘lat’));
            const urlLng = parseFloat(urlParams.get(‘lng’));

            const map = new google.maps.Map(document.getElementById(‘map’), {
                zoom: urlLat && urlLng ? 15 : 12,
                center: {
                    lat: urlLat || 41.311,
                    lng: urlLng || 69.279
                },
                mapTypeId: google.maps.MapTypeId.HYBRID
            });

            fetchUsdRate()
                .then(rate => {
                    usdRate = rate;
                    fetchMarkers(map, usdRate, urlLat, urlLng);
                    setupEventListeners();
                })
                .catch(error => {
                    console.error(‘Error fetching USD rate:’, error);
                    // Proceed without usdRate
                    fetchMarkers(map, usdRate, urlLat, urlLng);
                    setupEventListeners();
                });

            handleDistricts(map);
        }

        async function fetchUsdRate() {
            try {
                const response = await fetch(‘https://cbu.uz/uz/arkhiv-kursov-valyut/json/’);
                const rates = await response.json();
                const usdRateObj = rates.find(rate => rate.Ccy === ‘USD’);
                if (usdRateObj && usdRateObj.Rate) {
                    return parseFloat(usdRateObj.Rate);
                } else {
                    throw new Error(‘USD rate not found in API response’);
                }
            } catch (error) {
                console.error(‘Error fetching USD rate:’, error);
                return null;
            }
        }

        function fetchMarkers(map, usdRate, urlLat, urlLng) {
            fetch(‘/api/aktivs’)
                .then(response => response.json())
                .then(data => {
                    const markersData = data.lots;
                    window.markers = markersData;

                    let targetMarkerData = null;

                    markersData.forEach(markerData => {
                        const lat = parseFloat(markerData.lat);
                        const lng = parseFloat(markerData.lng);

                        if (!isNaN(lat) && !isNaN(lng)) {
                            const position = {
                                lat,
                                lng
                            };
                            const title = markerData.property_name || ‘No Title’;


                            let iconUrl;
                            if (markerData.building_type == ‘yer’) {
                                // Red icon
                                iconUrl = ‘http://maps.google.com/mapfiles/ms/icons/red-dot.png’;
                            } else if (markerData.building_type == ‘kopQavatliUy’) {
                                // Yellow icon
                                iconUrl = ‘http://maps.google.com/mapfiles/ms/icons/yellow-dot.png’;
                            } else if (markerData.building_type == ‘AlohidaSavdoDokoni’) {
                                iconUrl = ‘http://maps.google.com/mapfiles/ms/icons/blue-dot.png’;

                            } else {
                                iconUrl = ‘http://maps.google.com/mapfiles/ms/icons/green-dot.png’;

                            }

                            const marker = new google.maps.Marker({
                                position: position,
                                map: map,
                                title: title,
                                icon: iconUrl
                            });

                            // const marker = new google.maps.Marker({
                            //     position: position,
                            //     map: map,
                            //     title: title
                            // });

                            marker.addListener(‘click’, function() {
                                const sidebar = document.getElementById(‘info-sidebar’);
                                const isInUSD = sidebar.getAttribute(‘data-currency’) === ‘USD’;
                                updateSidebarContent(markerData, isInUSD, usdRate);
                                sidebar.classList.add(‘open’);
                            });


                            if (urlLat && urlLng && lat === urlLat && lng === urlLng) {
                                targetMarkerData = markerData;
                                map.setCenter({
                                    lat,
                                    lng
                                });
                                map.setZoom(15);
                            }
                        }
                    });


                    if (targetMarkerData) {
                        const sidebar = document.getElementById(‘info-sidebar’);
                        const isInUSD = sidebar.getAttribute(‘data-currency’) === ‘USD’;
                        updateSidebarContent(targetMarkerData, isInUSD, usdRate);
                        sidebar.classList.add(‘open’);
                    }
                })
                .catch(error => console.error(‘Error fetching markers:’, error));
        }

        function updateSidebarContent(markerData, isInUSD, usdRate) {
            const sidebar = document.getElementById(‘info-sidebar’);
            const area = parseFloat(markerData.land_area) || 0;
            const priceUZS = parseFloat(markerData.start_price) || 0;
            const lotPricePerSotixUZS = area > 0 ? priceUZS / (area * 100) : 0;

            let lotPriceFormatted = ‘N/A’;
            let lotPricePerSotixFormatted = ‘N/A’;

            try {
                if (isInUSD && usdRate && priceUZS > 0) {
                    const priceUSD = priceUZS / usdRate;
                    const pricePerSotixUSD = lotPricePerSotixUZS / usdRate;

                    lotPriceFormatted = new Intl.NumberFormat(‘en-US’, {
                        style: ‘currency’,
                        currency: ‘USD’
                    }).format(priceUSD);

                    lotPricePerSotixFormatted = new Intl.NumberFormat(‘en-US’, {
                        style: ‘currency’,
                        currency: ‘USD’
                    }).format(pricePerSotixUSD);
                } else if (priceUZS > 0) {
                    lotPriceFormatted = new Intl.NumberFormat(‘uz-UZ’, {
                        style: ‘currency’,
                        currency: ‘UZS’,
                        minimumFractionDigits: 0
                    }).format(priceUZS);

                    lotPricePerSotixFormatted = new Intl.NumberFormat(‘uz-UZ’, {
                        style: ‘currency’,
                        currency: ‘UZS’,
                        minimumFractionDigits: 0
                    }).format(lotPricePerSotixUZS);
                }
            } catch (error) {
                console.error(‘Error formatting currency:’, error);
            }

            const qrCodeUrl = `${baseUrl}/api/lot/qr-code/${markerData.lat}/${markerData.lng}`;

            sidebar.innerHTML = `
        <span class="close-btn">&times;</span>
        <div class="info-content">
            <img class="custom_sidebar_image" src="${markerData.main_image}" alt="Marker Image"/>
            <button id="toggle-currency-btn">${isInUSD ? ‘Valyutani tahrirlash UZS’ : ‘Valyutani tahrirlash USD’}</button>
            <h4 class="custom_sidebar_title"><b>${markerData.property_name || ‘No Title’}</b></h4>
            <table>
                <tr>
                    <th class="sidebar_key">Lot raqami</th>
                    <td>${markerData.lot_number || ‘N/A’}</td>
                </tr>
                <tr>
                    <th class="sidebar_key">Manzili</th>
                    <td>${markerData.address || ‘N/A’}</td>
                </tr>
                <tr>
                    <th class="sidebar_key">Yer maydoni (kv)</th>
                    <td>${markerData.land_area || ‘N/A’}</td>
                </tr>
                ${priceUZS > 0 ? `
                                <tr>
                                    <th class="sidebar_key">Boshlang’ich narxi</th>
                                    <td id="price-td">${lotPriceFormatted}</td>
                                </tr>
                                <tr>
                                    <th class="sidebar_key">1 sotix uchun narx</th>
                                    <td>${lotPricePerSotixFormatted}</td>
                                </tr>` : ‘‘}
                <tr>
                    <th class="sidebar_key">Yaratilgan foydalanuvchi</th>
                    <td>${markerData.user_name || ‘N/A’}</td>
                </tr>
                <tr>
                    <th class="sidebar_key">Email</th>
                    <td>${markerData.user_email || ‘N/A’}</td>
                </tr>
            </table>

            <a target="_blank" href="${markerData.lot_link || ‘#’}" class="btn-link">Aktivni ko’rish</a>
        </div>
    `;
        }


        function setupEventListeners() {
            document.addEventListener(‘click’, function(event) {
                if (event.target.matches(‘.close-btn’)) {
                    const sidebar = document.getElementById(‘info-sidebar’);
                    sidebar.classList.remove(‘open’);
                } else if (event.target.matches(‘#toggle-currency-btn’)) {
                    const sidebar = document.getElementById(‘info-sidebar’);
                    const isInUSD = sidebar.getAttribute(‘data-currency’) === ‘USD’;
                    sidebar.setAttribute(‘data-currency’, isInUSD ? ‘UZS’ : ‘USD’);
                    event.target.textContent = isInUSD ? ‘Valyutani tahrirlash USD’ : ‘Valyutani tahrirlash UZS’;

                    const currentTitle = sidebar.querySelector(‘.custom_sidebar_title b’).textContent;
                    const markerData = window.markers.find(marker => marker.property_name === currentTitle);

                    if (markerData) {
                        if (usdRate !== null || !isInUSD) {
                            updateSidebarContent(markerData, !isInUSD, usdRate);
                        } else {
                            alert(‘USD rate is not available at the moment. Please try again later.’);
                        }
                    }
                }
            });
        }

        function handleDistricts(map) {
            let polygons = {};
            let currentHighlight = null;

            const defaultColor = ‘#c7a5a594’;
            const highlightColor = ‘#EEF5FF’;

            const kmlFileNames = [
                ‘bektemir.xml’, ‘chilonzor.xml’, ‘mirabod.xml’, ‘mirzo_ulugbek.xml’, ‘olmazor.xml’,
                ‘sergeli.xml’, ‘shayhontaxur.xml’, ‘uchtepa.xml’, ‘yakkasaroy.xml’, ‘yashnabod.xml’,
                ‘yunusabod.xml’, ‘yangihayot.xml’
            ];

            kmlFileNames.forEach(fileName => {
                processKML(fileName, defaultColor, map, polygons);
            });

            document.getElementById(‘xml-selector’).addEventListener(‘change’, function(event) {
                const selectedFile = event.target.value;
                if (selectedFile) {
                    if (currentHighlight && currentHighlight !== ‘tashkent’) {
                        setPolygonColor(currentHighlight, defaultColor, polygons);
                    }

                    if (selectedFile === ‘tashkent’) {
                        map.setCenter({
                            lat: 41.311,
                            lng: 69.279
                        });
                        map.setZoom(12);
                        fetchDistrictInfo(‘tashkent’);
                        currentHighlight = ‘tashkent’;
                    } else {
                        processKML(selectedFile, highlightColor, map, polygons);
                        currentHighlight = selectedFile;
                        fetchDistrictInfo(selectedFile);
                    }
                }
            });

            function processKML(fileName, color, map, polygons) {
                fetch(`{{ asset(‘xml-map’) }}/${fileName}`)
                    .then(response => response.text())
                    .then(kmlText => {
                        const paths = parseKML(kmlText);
                        addPolygon(fileName, [paths], color, map, polygons);
                        const bounds = new google.maps.LatLngBounds();
                        paths.forEach(coord => bounds.extend(new google.maps.LatLng(coord.lat, coord.lng)));
                        map.fitBounds(bounds);
                    })
                    .catch(error => console.error(`Error fetching ${fileName}:`, error));
            }

            function addPolygon(fileName, paths, fillColor, map, polygons) {
                if (polygons[fileName]) {
                    polygons[fileName].forEach(polygon => polygon.setMap(null));
                }

                const polygonArray = paths.map(path => {
                    const polygon = new google.maps.Polygon({
                        paths: path,
                        strokeColor: ‘#fff’,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: fillColor,
                        fillOpacity: 0.35,
                        map: map
                    });
                    return polygon;
                });

                polygons[fileName] = polygonArray;
            }

            function setPolygonColor(fileName, color, polygons) {
                if (polygons[fileName]) {
                    polygons[fileName].forEach(polygon => polygon.setOptions({
                        fillColor: color
                    }));
                }
            }

            function parseKML(kmlText) {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(kmlText, "application/xml");
                const coordinates = xmlDoc.getElementsByTagName(‘coordinates’)[0]?.textContent.trim();
                return coordinates ? coordinates.split(‘ ‘).map(coord => {
                    const [lng, lat] = coord.split(‘,’).map(Number);
                    return {
                        lat,
                        lng
                    };
                }) : [];
            }

</script>
