@extends('layouts.admin')

@section('content')
    <h1>Янги Актив Яратиш</h1>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Расм олиш</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Ёпиш"></button>
                </div>
                <div class="modal-body">
                    <video id="cameraPreview" width="100%" autoplay></video>
                    <canvas id="snapshotCanvas" style="display:none;"></canvas>
                    <div id="cameraError" class="text-danger"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="captureButton" disabled>Расм олиш</button>
                    <button type="button" class="btn btn-primary" id="saveButton" data-bs-dismiss="modal"
                        disabled>Сақлаш</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('aktivs.store') }}" enctype="multipart/form-data" id="aktiv-form">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1 }}">
        <div class="row my-3">
            <!-- Left Column -->
            <div class="col-md-6">
                <p>Yangi ma'lumotlar</p>

                <!-- 1) Турар жой майдони -->
                <div class="mb-3">
                    <label for="turar_joy_maydoni">Турар жой майдони</label>
                    <input class="form-control" type="number" step="0.01" name="turar_joy_maydoni"
                        id="turar_joy_maydoni" value="{{ old('turar_joy_maydoni') }}">
                    @error('turar_joy_maydoni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <!-- 2) Нотурар жой майдони -->
                <div class="mb-3">
                    <label for="noturar_joy_maydoni">Нотурар жой майдони</label>
                    <input class="form-control" type="number" step="0.01" name="noturar_joy_maydoni"
                        id="noturar_joy_maydoni" value="{{ old('noturar_joy_maydoni') }}">
                    @error('noturar_joy_maydoni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 3) Вақтинчалик тўхташ жойи маълумот -->
                <div class="mb-3">
                    <label for="vaqtinchalik_parking_info">Вақтинчалик тўхташ жойи маълумот</label>
                    <textarea class="form-control" name="vaqtinchalik_parking_info" id="vaqtinchalik_parking_info" rows="2">{{ old('vaqtinchalik_parking_info') }}</textarea>
                    @error('vaqtinchalik_parking_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 4) Доимий тўхташ жойи маълумот -->
                <div class="mb-3">
                    <label for="doimiy_parking_info">Доимий тўхташ жойи маълумот</label>
                    <textarea class="form-control" name="doimiy_parking_info" id="doimiy_parking_info" rows="2">{{ old('doimiy_parking_info') }}</textarea>
                    @error('doimiy_parking_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 5) Мактабгача таълим ташкилоти маълумот -->
                <div class="mb-3">
                    <label for="maktabgacha_tashkilot_info">Мактабгача таълим ташкилоти маълумот</label>
                    <textarea class="form-control" name="maktabgacha_tashkilot_info" id="maktabgacha_tashkilot_info" rows="2">{{ old('maktabgacha_tashkilot_info') }}</textarea>
                    @error('maktabgacha_tashkilot_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 6) Умумтаълим мактаби маълумот -->
                <div class="mb-3">
                    <label for="umumtaolim_maktab_info">Умумтаълим мактаби маълумот</label>
                    <textarea class="form-control" name="umumtaolim_maktab_info" id="umumtaolim_maktab_info" rows="2">{{ old('umumtaolim_maktab_info') }}</textarea>
                    @error('umumtaolim_maktab_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 7) Стационар тиббиёт муассасаси маълумот -->
                <div class="mb-3">
                    <label for="stasionar_tibbiyot_info">Стационар тиббиёт муассасаси маълумот</label>
                    <textarea class="form-control" name="stasionar_tibbiyot_info" id="stasionar_tibbiyot_info" rows="2">{{ old('stasionar_tibbiyot_info') }}</textarea>
                    @error('stasionar_tibbiyot_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 8) Амбулатор тиббиёт муассасаси маълумот -->
                <div class="mb-3">
                    <label for="ambulator_tibbiyot_info">Амбулатор тиббиёт муассасаси маълумот</label>
                    <textarea class="form-control" name="ambulator_tibbiyot_info" id="ambulator_tibbiyot_info" rows="2">{{ old('ambulator_tibbiyot_info') }}</textarea>
                    @error('ambulator_tibbiyot_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 9) Диний муассасаси маълумот -->
                <div class="mb-3">
                    <label for="diniy_muassasa_info">Диний муассасаси маълумот</label>
                    <textarea class="form-control" name="diniy_muassasa_info" id="diniy_muassasa_info" rows="2">{{ old('diniy_muassasa_info') }}</textarea>
                    @error('diniy_muassasa_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 10) Спорт-соғломлаштириш муассасаси маълумот -->
                <div class="mb-3">
                    <label for="sport_soglomlashtirish_info">Спорт-соғломлаштириш муассасаси маълумот</label>
                    <textarea class="form-control" name="sport_soglomlashtirish_info" id="sport_soglomlashtirish_info" rows="2">{{ old('sport_soglomlashtirish_info') }}</textarea>
                    @error('sport_soglomlashtirish_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 11) Сақлаб қолинадиган (ёки реконструкция) кўкаламзорлаштириш маълумот -->
                <div class="mb-3">
                    <label for="saqlanadigan_kokalamzor_info">Сақланадиган кўкаламзорлаштириш маълумот</label>
                    <textarea class="form-control" name="saqlanadigan_kokalamzor_info" id="saqlanadigan_kokalamzor_info" rows="2">{{ old('saqlanadigan_kokalamzor_info') }}</textarea>
                    @error('saqlanadigan_kokalamzor_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 12) Янгидан ташкил қилинадиган кўкаламзорлаштириш маълумот -->
                <div class="mb-3">
                    <label for="yangidan_tashkil_kokalamzor_info">Янгидан ташкил қилинадиган кўкаламзорлаштириш
                        маълумот</label>
                    <textarea class="form-control" name="yangidan_tashkil_kokalamzor_info" id="yangidan_tashkil_kokalamzor_info"
                        rows="2">{{ old('yangidan_tashkil_kokalamzor_info') }}</textarea>
                    @error('yangidan_tashkil_kokalamzor_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 13) Сақланиб қолинадиган (ёки реконстр.) муҳандислик-коммуникация тармоқлари маълумот -->
                <div class="mb-3">
                    <label for="saqlanadigan_muhandislik_tarmoqlari_info">Сақланадиган муҳандислик-коммуникация
                        тармоқлари</label>
                    <textarea class="form-control" name="saqlanadigan_muhandislik_tarmoqlari_info"
                        id="saqlanadigan_muhandislik_tarmoqlari_info" rows="2">{{ old('saqlanadigan_muhandislik_tarmoqlari_info') }}</textarea>
                    @error('saqlanadigan_muhandislik_tarmoqlari_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 14) Янгидан қуриладиган муҳандислик-коммуникация тармоқлари маълумот -->
                <div class="mb-3">
                    <label for="yangidan_quriladigan_muhandislik_tarmoqlari_info">Янгидан қуриладиган
                        муҳандислик-коммуникация тармоқлари</label>
                    <textarea class="form-control" name="yangidan_quriladigan_muhandislik_tarmoqlari_info"
                        id="yangidan_quriladigan_muhandislik_tarmoqlari_info" rows="2">{{ old('yangidan_quriladigan_muhandislik_tarmoqlari_info') }}</textarea>
                    @error('yangidan_quriladigan_muhandislik_tarmoqlari_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 15) Сақланиб қолинадиган (ёки реконстр.) йўллар ва йўлаклар маълумот -->
                <div class="mb-3">
                    <label for="saqlanadigan_yollar_info">Сақланадиган йўллар ва йўлаклар</label>
                    <textarea class="form-control" name="saqlanadigan_yollar_info" id="saqlanadigan_yollar_info" rows="2">{{ old('saqlanadigan_yollar_info') }}</textarea>
                    @error('saqlanadigan_yollar_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 16) Янгидан қуриладиган йўллар ва йўлаклар маълумот -->
                <div class="mb-3">
                    <label for="yangidan_quriladigan_yollar_info">Янгидан қуриладиган йўллар ва йўлаклар</label>
                    <textarea class="form-control" name="yangidan_quriladigan_yollar_info" id="yangidan_quriladigan_yollar_info"
                        rows="2">{{ old('yangidan_quriladigan_yollar_info') }}</textarea>
                    @error('yangidan_quriladigan_yollar_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="1-etap-protokol">1-etap protokol</label>
                    <input class="form-control" type="file" name="1-etap-protokol" id="1-etap-protokol">
                    @error('1-etap-protokol')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="2-etap-protokol">2-etap protokol</label>
                    <input class="form-control" type="file" name="2-etap-protokol" id="2-etap-protokol">
                    @error('2-etap-protokol')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="1-etap-elon">1-etap elon</label>
                    <input class="form-control" type="file" name="1-etap-elon" id="1-etap-elon">
                    @error('1-etap-elon')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="2-etap-elon">2-etap elon</label>
                    <input class="form-control" type="file" name="2-etap-elon" id="2-etap-elon">
                    @error('2-etap-elon')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="zayavka">Ariza</label>
                    <input class="form-control" type="file" name="zayavka" id="zayavka">
                    @error('zayavka')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="hokim_qarori">Hokim qarori</label>
                    <input class="form-control" type="file" name="hokim_qarori" id="hokim_qarori">
                    @error('hokim_qarori')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="others">Boshqa hujjatlar</label>
                    <input class="form-control" type="file" name="others" id="others">
                    @error('others')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PolygonAktiv fields -->
                <div id="polygonAktivsContainer">
                    <div class="polygon-aktiv-block">
                        <div class="form-group">
                            <label for="start_lat_0">Start Latitude</label>
                            <input type="text" name="polygon_aktivs[0][start_lat]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="start_lon_0">Start Longitude</label>
                            <input type="text" name="polygon_aktivs[0][start_lon]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end_lat_0">End Latitude</label>
                            <input type="text" name="polygon_aktivs[0][end_lat]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end_lon_0">End Longitude</label>
                            <input type="text" name="polygon_aktivs[0][end_lon]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="distance_0">Distance</label>
                            <input type="number" name="polygon_aktivs[0][distance]" class="form-control">
                        </div>
                        <button type="button" class="remove-polygon-aktiv btn btn-danger">Remove</button>
                    </div>
                </div>
                <button type="button" id="addPolygonAktivBtn" class="btn btn-primary">Add Polygon Aktiv</button>


                <script>
                    document.getElementById('addPolygonAktivBtn').addEventListener('click', function() {
                        var container = document.getElementById('polygonAktivsContainer');
                        var index = container.children.length;
                        var newBlock = document.createElement('div');
                        newBlock.classList.add('polygon-aktiv-block');
                        newBlock.innerHTML = `
            <div class="form-group">
                <label for="start_lat_${index}">Start Latitude</label>
                <input type="text" name="polygon_aktivs[${index}][start_lat]" class="form-control">
            </div>
            <div class="form-group">
                <label for="start_lon_${index}">Start Longitude</label>
                <input type="text" name="polygon_aktivs[${index}][start_lon]" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_lat_${index}">End Latitude</label>
                <input type="text" name="polygon_aktivs[${index}][end_lat]" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_lon_${index}">End Longitude</label>
                <input type="text" name="polygon_aktivs[${index}][end_lon]" class="form-control">
            </div>
            <div class="form-group">
                <label for="distance_${index}">Distance</label>
                <input type="number" name="polygon_aktivs[${index}][distance]" class="form-control">
            </div>
            <button type="button" class="remove-polygon-aktiv btn btn-danger">Remove</button>
        `;
                        container.appendChild(newBlock);
                    });

                    document.addEventListener('click', function(event) {
                        if (event.target.classList.contains('remove-polygon-aktiv')) {
                            event.target.closest('.polygon-aktiv-block').remove();
                        }
                    });
                </script>


                {{-- end new fields -------------------------- --}}
                <!-- Form Inputs -->
                <div class="mb-3">
                    <label for="object_name">Объект номи</label>
                    <input class="form-control" type="text" name="object_name" id="object_name"
                        value="{{ old('object_name') }}">
                    @error('object_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="balance_keeper">Балансда сақловчи</label>
                    <input class="form-control" type="text" name="balance_keeper" id="balance_keeper"
                        value="{{ old('balance_keeper') }}">
                    @error('balance_keeper')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="location">Мўлжал</label>
                    <input class="form-control" type="text" name="location" id="location"
                        value="{{ old('location') }}">
                    @error('location')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="land_area">Ер майдони (кв.м)</label>
                    <input class="form-control" type="number" name="land_area" id="land_area"
                        value="{{ old('land_area') }}">
                    @error('land_area')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="building_area">Бино майдони (кв.м)</label>
                    <input class="form-control" type="number" name="building_area" id="building_area"
                        value="{{ old('building_area') }}">
                    @error('building_area')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <label for="gas">Газ</label>
                <select class="form-control form-select mb-3" name="gas" id="gas">
                    <option value="Мавжуд" {{ old('gas') == 'Мавжуд' ? 'selected' : '' }}>Мавжуд</option>
                    <option value="Мавжуд эмас" {{ old('gas') == 'Мавжуд эмас' ? 'selected' : '' }}>Мавжуд эмас</option>
                </select>
                @error('gas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <label for="water">Сув</label>
                <select class="form-control form-select mb-3" name="water" id="water">
                    <option value="Мавжуд" {{ old('water') == 'Мавжуд' ? 'selected' : '' }}>Мавжуд</option>
                    <option value="Мавжуд эмас" {{ old('water') == 'Мавжуд эмас' ? 'selected' : '' }}>Мавжуд эмас</option>
                </select>
                @error('water')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <label for="electricity">Электр</label>
                <select class="form-control form-select mb-3" name="electricity" id="electricity">
                    <option value="Мавжуд" {{ old('electricity') == 'Мавжуд' ? 'selected' : '' }}>Мавжуд</option>
                    <option value="Мавжуд эмас" {{ old('electricity') == 'Мавжуд эмас' ? 'selected' : '' }}>Мавжуд эмас
                    </option>
                </select>
                @error('electricity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label for="additional_info">Қўшимча маълумот</label>
                    <input class="form-control" type="text" name="additional_info" id="additional_info"
                        value="{{ old('additional_info') }}">
                    @error('additional_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @include('inc.__address')
            </div>
            <!-- Right Column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-danger">Файлларни юклаш (Камида 4 та расм мажбурий)</label>
                </div>

                <div id="fileInputsContainer">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="mb-3" id="fileInput{{ $i }}">
                            <label for="file{{ $i }}">Файл {{ $i }}</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="files[]" id="file{{ $i }}"
                                    accept="image/*" required>
                                <button type="button" class="btn btn-secondary"
                                    onclick="openCameraModal('file{{ $i }}')">📷</button>
                            </div>
                        </div>
                    @endfor
                </div>

                <div id="file-error" class="text-danger mb-3"></div>
                <div id="file-upload-container"></div>
                <button type="button" class="btn btn-secondary mb-3" id="add-file-btn">Янги файл қўшиш</button>

                <div class="mb-3">
                    <button id="find-my-location" type="button" class="btn btn-primary mb-3">Менинг жойлашувимни
                        топиш</button>
                    <div id="map" style="height: 500px; width: 100%;"></div>
                    @error('latitude')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @error('longitude')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                <div class="mb-3">
                    <label for="geolokatsiya">Геолокация (координата)</label>
                    <input class="form-control" type="text" name="geolokatsiya" id="geolokatsiya" readonly required
                        value="{{ old('geolokatsiya') }}">
                    @error('geolokatsiya')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success" id="submit-btn">Сақлаш</button>
    </form>
@endsection

@section('scripts')
    <!-- Include Google Maps JavaScript API with Places Library -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAnUwWTguBMsDU8UrQ7Re-caVeYCmcHQY&libraries&libraries=places&callback=initMap"
        async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Wrap the entire script in an IIFE to avoid global scope pollution
        (function() {
            let fileInputCount = 4;
            let activeFileInput = null;
            let videoStream = null;
            let map, marker, infoWindow;

            // Parse the aktivs data from the server-side variable
            let aktivs = @json($aktivs ?? []);

            // Wait for the DOM to be fully loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize event listeners and other setup tasks
                initializeEventListeners();
                initializeFileInputs();
                validateFiles();
                initMap();
            });

            function initializeEventListeners() {
                // Camera modal elements
                const captureButton = document.getElementById('captureButton');
                const saveButton = document.getElementById('saveButton');
                const cameraPreview = document.getElementById('cameraPreview');
                const cameraError = document.getElementById('cameraError');

                // Form elements
                const addFileBtn = document.getElementById('add-file-btn');
                const submitBtn = document.getElementById('submit-btn');
                const aktivForm = document.getElementById('aktiv-form');

                // Map elements
                const findMyLocationBtn = document.getElementById('find-my-location');

                // Add event listeners if the elements exist
                if (captureButton) {
                    captureButton.addEventListener('click', capturePhoto);
                }

                if (saveButton) {
                    saveButton.addEventListener('click', savePhoto);
                }

                if (addFileBtn) {
                    addFileBtn.addEventListener('click', addFileInput);
                }

                if (aktivForm) {
                    aktivForm.addEventListener('submit', handleFormSubmit);
                }

                if (findMyLocationBtn) {
                    findMyLocationBtn.addEventListener('click', findMyLocation);
                }
            }

            function initializeFileInputs() {
                // Initialize existing file inputs
                for (let i = 1; i <= fileInputCount; i++) {
                    const fileInput = document.getElementById('file' + i);
                    if (fileInput) {
                        fileInput.addEventListener('change', validateFiles);
                    }
                }
            }

            function openCameraModal(fileInputId) {
                activeFileInput = document.getElementById(fileInputId);
                const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'), {});
                cameraModal.show();

                // Check for camera support
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices
                        .getUserMedia({
                            video: {
                                facingMode: 'environment',
                            },
                        })
                        .then((stream) => {
                            videoStream = stream;
                            document.getElementById('cameraPreview').srcObject = stream;
                            document.getElementById('captureButton').disabled = false;
                            document.getElementById('cameraError').textContent = '';
                        })
                        .catch((error) => {
                            document.getElementById('cameraError').textContent =
                                'Камерага кириш мумкин эмас: ' + error.message;
                            document.getElementById('captureButton').disabled = true;
                        });
                } else {
                    document.getElementById('cameraError').textContent =
                        'Браузерингиз камерадан фойдаланишни қўллаб-қувватламайди.';
                    document.getElementById('captureButton').disabled = true;
                }
            }

            function capturePhoto() {
                const video = document.getElementById('cameraPreview');
                const canvas = document.getElementById('snapshotCanvas');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                if (videoStream) {
                    videoStream.getTracks().forEach((track) => track.stop());
                }
                document.getElementById('saveButton').disabled = false;
            }

            function savePhoto() {
                const canvas = document.getElementById('snapshotCanvas');
                canvas.toBlob((blob) => {
                    const file = new File([blob], `snapshot-${Date.now()}.jpg`, {
                        type: 'image/jpeg',
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    if (activeFileInput) {
                        activeFileInput.files = dataTransfer.files;
                        validateFiles();
                    }
                });
                document.getElementById('cameraPreview').srcObject = null;
                document.getElementById('saveButton').disabled = true;
            }

            function addFileInput() {
                fileInputCount++;
                const container = document.getElementById('file-upload-container');
                const newDiv = document.createElement('div');
                newDiv.classList.add('mb-3');

                const label = document.createElement('label');
                label.textContent = `Қўшимча файл ${fileInputCount}`;

                const inputGroup = document.createElement('div');
                inputGroup.classList.add('input-group');

                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'files[]';
                input.classList.add('form-control');
                input.accept = 'image/*';
                input.required = true;
                input.id = 'file' + fileInputCount;
                input.addEventListener('change', validateFiles);

                const button = document.createElement('button');
                button.type = 'button';
                button.classList.add('btn', 'btn-secondary');
                button.textContent = '📷';
                button.addEventListener('click', function() {
                    openCameraModal(input.id);
                });

                inputGroup.appendChild(input);
                inputGroup.appendChild(button);
                newDiv.appendChild(label);
                newDiv.appendChild(inputGroup);
                container.appendChild(newDiv);
                validateFiles();
            }

            function validateFiles() {
                const submitBtn = document.getElementById('submit-btn');
                const errorDiv = document.getElementById('file-error');
                const fileInputs = document.querySelectorAll('input[type="file"][name="files[]"]');
                let totalFiles = 0;

                fileInputs.forEach((input) => {
                    if (input.files.length > 0) {
                        totalFiles += input.files.length;
                    }
                });

                if (totalFiles < 4) {
                    let filesNeeded = 4 - totalFiles;
                    errorDiv.textContent =
                        filesNeeded === 4 ?
                        'Сиз ҳеч қандай файл юкламадингиз.' :
                        `Сиз яна ${filesNeeded} та файл юклашингиз керак.`;
                    submitBtn.disabled = true;
                } else {
                    errorDiv.textContent = '';
                    submitBtn.disabled = false;
                }
            }

            function handleFormSubmit(event) {
                validateFiles();
                const submitBtn = document.getElementById('submit-btn');
                if (submitBtn.disabled) {
                    event.preventDefault();
                } else {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Юкланмоқда...';
                }
            }

            function findMyLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            map.setCenter(userLocation);
                            map.setZoom(15);
                            placeMarker(userLocation);
                        },
                        function(error) {
                            alert('Жойлашувингиз аниқланмади: ' + error.message);
                        }
                    );
                } else {
                    alert('Жойлашувни аниқлаш браузерингиз томонидан қўлланилмайди.');
                }
            }

            function initMap() {
                const mapOptions = {
                    center: {
                        lat: 41.2995,
                        lng: 69.2401,
                    },
                    zoom: 10,
                };
                map = new google.maps.Map(document.getElementById('map'), mapOptions);
                infoWindow = new google.maps.InfoWindow();

                // Existing aktivs markers
                if (aktivs && aktivs.length > 0) {
                    aktivs.forEach((aktiv) => {
                        if (aktiv.latitude && aktiv.longitude) {
                            const position = {
                                lat: parseFloat(aktiv.latitude),
                                lng: parseFloat(aktiv.longitude),
                            };

                            const aktivMarker = new google.maps.Marker({
                                position: position,
                                map: map,
                                title: aktiv.object_name,
                                icon: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
                            });

                            aktivMarker.addListener('click', function() {
                                openInfoWindow(aktiv, aktivMarker);
                            });
                        }
                    });
                }

                // Add click event to place custom marker
                map.addListener('click', function(event) {
                    placeMarker(event.latLng);
                });

                // If latitude and longitude are already set, place a marker
                const latInput = document.getElementById('latitude').value;
                const lngInput = document.getElementById('longitude').value;
                if (latInput && lngInput) {
                    const position = {
                        lat: parseFloat(latInput),
                        lng: parseFloat(lngInput),
                    };
                    placeMarker(position);
                    map.setCenter(position);
                    map.setZoom(15);
                }
            }

            function openInfoWindow(aktiv, marker) {
                const mainImagePath =
                    aktiv.files && aktiv.files.length > 0 ?
                    `/storage/${aktiv.files[0].path}` :
                    'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

                const contentString = `
              <div style="width:250px;">
                  <h5>${aktiv.object_name}</h5>
                  <img src="${mainImagePath}" alt="Marker Image" style="width:100%;height:auto;"/>
                  <p><strong>Балансда сақловчи:</strong> ${aktiv.balance_keeper || 'N/A'}</p>
                  <p><strong>Мўлжал:</strong> ${aktiv.location || 'N/A'}</p>
                  <p><strong>Ер майдони (кв.м):</strong> ${aktiv.land_area || 'N/A'}</p>
                  <p><strong>Бино майдони (кв.м):</strong> ${aktiv.building_area || 'N/A'}</p>
                  <p><strong>Газ:</strong> ${aktiv.gas || 'N/A'}</p>
                  <p><strong>Сув:</strong> ${aktiv.water || 'N/A'}</p>
                  <p><strong>Электр:</strong> ${aktiv.electricity || 'N/A'}</p>
                  <p><strong>Қўшимча маълумот:</strong> ${aktiv.additional_info || 'N/A'}</p>
                  <p><strong>Қарта:</strong> <a href="${aktiv.geolokatsiya || '#'}" target="_blank">${
              aktiv.geolokatsiya || 'N/A'
            }</a></p>
              </div>
            `;

                infoWindow.setContent(contentString);
                infoWindow.open(map, marker);
            }

            function placeMarker(location) {
                if (marker) {
                    marker.setMap(null);
                }
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });

                const lat = typeof location.lat === 'function' ? location.lat() : location.lat;
                const lng = typeof location.lng === 'function' ? location.lng() : location.lng;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('geolokatsiya').value = `https://www.google.com/maps?q=${lat},${lng}`;
            }

            // Expose initMap to the global scope for Google Maps callback
            window.initMap = initMap;
        })();
    </script>
@endsection
