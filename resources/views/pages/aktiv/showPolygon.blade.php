<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinates Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <style>
        #map {
            height: 100vh;
        }
    </style>
</head>

<body>
    <h2>Coordinates on the Map</h2>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Function to clean and convert DMS to Decimal Degrees
        function dmsToDecimal(dms) {
            if (!dms) return null;

            // Remove unwanted characters
            dms = dms.replace(/[^\d°'".NSWE]/g, '').trim();

            // Extract parts using regex
            const regex = /(\d+)[°](\d+)?[']?(\d+(\.\d+)?["]?)?/;
            const match = dms.match(regex);

            if (!match) {
                console.warn('Invalid DMS format:', dms);
                return null;
            }

            const degrees = parseFloat(match[1]);
            const minutes = match[2] ? parseFloat(match[2]) / 60 : 0;
            const seconds = match[3] ? parseFloat(match[3]) / 3600 : 0;
            let decimal = degrees + minutes + seconds;

            // Adjust for direction
            if (/S|W/.test(dms)) decimal = -decimal;

            return decimal;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const aktivId = 1; // Set the Aktiv ID

            // Fetch polygon data from the server
            fetch(`http://127.0.0.1:8000/aktivs/${aktivId}/polygons`)
                .then(response => response.json())
                .then(polygonsData => {
                    initMap(polygonsData);
                })
                .catch(error => console.error('Error fetching polygon data:', error));

            function initMap(polygonsData) {
                const map = L.map('map').setView([41.2995, 69.2401], 10);

                // Set up OpenStreetMap layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map);

                drawPolygons(polygonsData, map);
            }

            function dmsToDecimal(dms) {
                if (!dms || typeof dms !== 'string') return null;

                dms = dms.replace(/[^\d°'".]/g, '').trim();

                const parts = dms.match(/(\d{1,3})°(\d{1,2})'(\d{1,2}(?:\.\d+)?)"/);
                if (!parts) return null;

                const degrees = parseFloat(parts[1]);
                const minutes = parseFloat(parts[2]) / 60;
                const seconds = parseFloat(parts[3]) / 3600;
                return degrees + minutes + seconds;
            }

            function drawPolygons(polygonsData, map) {
                polygonsData.forEach(polygonCoords => {
                    const startLat = polygonCoords.start.lat;
                    const startLng = polygonCoords.start.lng;
                    const endLat = polygonCoords.end.lat;
                    const endLng = polygonCoords.end.lng;

                    if (startLat !== null && startLng !== null && endLat !== null && endLng !== null) {
                        const polygonPath = [
                            [startLat, startLng],
                            [endLat, endLng]
                        ];

                        L.polygon(polygonPath, {
                            color: 'red',
                            fillColor: 'yellow',
                            fillOpacity: 0.5
                        }).addTo(map);
                    } else {
                        console.warn('Invalid coordinates for polygon:', polygonCoords);
                    }
                });
            }

        });
    </script>
</body>

</html>
