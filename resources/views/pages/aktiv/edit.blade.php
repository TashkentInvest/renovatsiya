@extends('layouts.admin')

@section('content')
    <h1>Активни таҳрирлаш</h1>

    <form method="POST" action="{{ route('aktivs.update', $aktiv->id) }}" enctype="multipart/form-data" id="aktiv-form">
        @csrf
        @method('PUT')

        <input type="hidden" name="user_id" value="{{ $aktiv->user_id }}">
        <div class="row my-3">
            <!-- Left Column -->
            <div class="col-md-6">

                <p>Yangi ma'lumotlar</p>
                <!-- 1) Турар жой майдони -->
                <div class="mb-3">
                    <label for="turar_joy_maydoni">Турар жой майдони</label>
                    <input class="form-control" type="number" step="0.01" name="turar_joy_maydoni"
                        id="turar_joy_maydoni" value="{{ old('turar_joy_maydoni', $aktiv->turar_joy_maydoni) }}">
                    @error('turar_joy_maydoni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 2) Нотурар жой майдони -->
                <div class="mb-3">
                    <label for="noturar_joy_maydoni">Нотурар жой майдони</label>
                    <input class="form-control" type="number" step="0.01" name="noturar_joy_maydoni"
                        id="noturar_joy_maydoni" value="{{ old('noturar_joy_maydoni', $aktiv->noturar_joy_maydoni) }}">
                    @error('noturar_joy_maydoni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 3) Вақтинчалик тўхташ жойи маълумот -->
                <div class="mb-3">
                    <label for="vaqtinchalik_parking_info">Вақтинчалик тўхташ жойи маълумот</label>
                    <textarea class="form-control" name="vaqtinchalik_parking_info" id="vaqtinchalik_parking_info" rows="2">{{ old('vaqtinchalik_parking_info', $aktiv->vaqtinchalik_parking_info) }}</textarea>
                    @error('vaqtinchalik_parking_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 4) Доимий тўхташ жойи маълумот -->
                <div class="mb-3">
                    <label for="doimiy_parking_info">Доимий тўхташ жойи маълумот</label>
                    <textarea class="form-control" name="doimiy_parking_info" id="doimiy_parking_info" rows="2">{{ old('doimiy_parking_info', $aktiv->doimiy_parking_info) }}</textarea>
                    @error('doimiy_parking_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 5) Мактабгача таълим ташкилоти маълумот -->
                <div class="mb-3">
                    <label for="maktabgacha_tashkilot_info">Мактабгача таълим ташкилоти маълумот</label>
                    <textarea class="form-control" name="maktabgacha_tashkilot_info" id="maktabgacha_tashkilot_info" rows="2">{{ old('maktabgacha_tashkilot_info', $aktiv->maktabgacha_tashkilot_info) }}</textarea>
                    @error('maktabgacha_tashkilot_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 6) Умумтаълим мактаби маълумот -->
                <div class="mb-3">
                    <label for="umumtaolim_maktab_info">Умумтаълим мактаби маълумот</label>
                    <textarea class="form-control" name="umumtaolim_maktab_info" id="umumtaolim_maktab_info" rows="2">{{ old('umumtaolim_maktab_info', $aktiv->umumtaolim_maktab_info) }}</textarea>
                    @error('umumtaolim_maktab_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 7) Стационар тиббиёт муассасаси маълумот -->
                <div class="mb-3">
                    <label for="stasionar_tibbiyot_info">Стационар тиббиёт муассасаси маълумот</label>
                    <textarea class="form-control" name="stasionar_tibbiyot_info" id="stasionar_tibbiyot_info" rows="2">{{ old('stasionar_tibbiyot_info', $aktiv->stasionar_tibbiyot_info) }}</textarea>
                    @error('stasionar_tibbiyot_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 8) Амбулатор тиббиёт муассасаси маълумот -->
                <div class="mb-3">
                    <label for="ambulator_tibbiyot_info">Амбулатор тиббиёт муассасаси маълумот</label>
                    <textarea class="form-control" name="ambulator_tibbiyot_info" id="ambulator_tibbiyot_info" rows="2">{{ old('ambulator_tibbiyot_info', $aktiv->ambulator_tibbiyot_info) }}</textarea>
                    @error('ambulator_tibbiyot_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 9) Диний муассасаси маълумот -->
                <div class="mb-3">
                    <label for="diniy_muassasa_info">Диний муассасаси маълумот</label>
                    <textarea class="form-control" name="diniy_muassasa_info" id="diniy_muassasa_info" rows="2">{{ old('diniy_muassasa_info', $aktiv->diniy_muassasa_info) }}</textarea>
                    @error('diniy_muassasa_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 10) Спорт-соғломлаштириш муассасаси маълумот -->
                <div class="mb-3">
                    <label for="sport_soglomlashtirish_info">Спорт-соғломлаштириш муассасаси маълумот</label>
                    <textarea class="form-control" name="sport_soglomlashtirish_info" id="sport_soglomlashtirish_info" rows="2">{{ old('sport_soglomlashtirish_info', $aktiv->sport_soglomlashtirish_info) }}</textarea>
                    @error('sport_soglomlashtirish_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 11) Сақланадиган кўкаламзорлаштириш маълумот -->
                <div class="mb-3">
                    <label for="saqlanadigan_kokalamzor_info">Сақланадиган кўкаламзорлаштириш маълумот</label>
                    <textarea class="form-control" name="saqlanadigan_kokalamzor_info" id="saqlanadigan_kokalamzor_info" rows="2">{{ old('saqlanadigan_kokalamzor_info', $aktiv->saqlanadigan_kokalamzor_info) }}</textarea>
                    @error('saqlanadigan_kokalamzor_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 12) Янгидан ташкил қилинадиган кўкаламзорлаштириш маълумот -->
                <div class="mb-3">
                    <label for="yangidan_tashkil_kokalamzor_info">Янгидан ташкил қилинадиган кўкаламзорлаштириш
                        маълумот</label>
                    <textarea class="form-control" name="yangidan_tashkil_kokalamzor_info" id="yangidan_tashkil_kokalamzor_info"
                        rows="2">{{ old('yangidan_tashkil_kokalamzor_info', $aktiv->yangidan_tashkil_kokalamzor_info) }}</textarea>
                    @error('yangidan_tashkil_kokalamzor_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 13) Сақланиб қолинадиган муҳандислик-коммуникация тармоқлари -->
                <div class="mb-3">
                    <label for="saqlanadigan_muhandislik_tarmoqlari_info">Сақланадиган муҳандислик-коммуникация
                        тармоқлари</label>
                    <textarea class="form-control" name="saqlanadigan_muhandislik_tarmoqlari_info"
                        id="saqlanadigan_muhandislik_tarmoqlari_info" rows="2">{{ old('saqlanadigan_muhandislik_tarmoqlari_info', $aktiv->saqlanadigan_muhandislik_tarmoqlari_info) }}</textarea>
                    @error('saqlanadigan_muhandislik_tarmoqlari_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 14) Янгидан қуриладиган муҳандислик-коммуникация тармоқлари -->
                <div class="mb-3">
                    <label for="yangidan_quriladigan_muhandislik_tarmoqlari_info">Янгидан қуриладиган
                        муҳандислик-коммуникация тармоқлари</label>
                    <textarea class="form-control" name="yangidan_quriladigan_muhandislik_tarmoqlari_info"
                        id="yangidan_quriladigan_muhandislik_tarmoqlari_info" rows="2">{{ old('yangidan_quriladigan_muhandislik_tarmoqlari_info', $aktiv->yangidan_quriladigan_muhandislik_tarmoqlari_info) }}</textarea>
                    @error('yangidan_quriladigan_muhandislik_tarmoqlari_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 15) Сақланиб қолинадиган йўллар ва йўлаклар -->
                <div class="mb-3">
                    <label for="saqlanadigan_yollar_info">Сақланадиган йўллар ва йўлаклар</label>
                    <textarea class="form-control" name="saqlanadigan_yollar_info" id="saqlanadigan_yollar_info" rows="2">{{ old('saqlanadigan_yollar_info', $aktiv->saqlanadigan_yollar_info) }}</textarea>
                    @error('saqlanadigan_yollar_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 16) Янгидан қуриладиган йўллар ва йўлаклар -->
                <div class="mb-3">
                    <label for="yangidan_quriladigan_yollar_info">Янгидан қуриладиган йўллар ва йўлаклар</label>
                    <textarea class="form-control" name="yangidan_quriladigan_yollar_info" id="yangidan_quriladigan_yollar_info"
                        rows="2">{{ old('yangidan_quriladigan_yollar_info', $aktiv->yangidan_quriladigan_yollar_info) }}</textarea>
                    @error('yangidan_quriladigan_yollar_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>



                {{-- end new fields ---------------- --}}
                <!-- Form Inputs -->
                <div class="mb-3">
                    <label for="object_name">Объект номи</label>
                    <input class="form-control" type="text" name="object_name" id="object_name"
                        value="{{ old('object_name', $aktiv->object_name) }}">
                    @error('object_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="balance_keeper">Балансда сақловчи</label>
                    <input class="form-control" type="text" name="balance_keeper" id="balance_keeper"
                        value="{{ old('balance_keeper', $aktiv->balance_keeper) }}">
                    @error('balance_keeper')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="location">Мўлжал</label>
                    <input class="form-control" type="text" name="location" id="location"
                        value="{{ old('location', $aktiv->location) }}">
                    @error('location')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="land_area">Ер майдони (кв.м)</label>
                    <input class="form-control" type="number" name="land_area" id="land_area"
                        value="{{ old('land_area', $aktiv->land_area) }}">
                    @error('land_area')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="building_area">Бино майдони (кв.м)</label>
                    <input class="form-control" type="number" name="building_area" id="building_area"
                        value="{{ old('building_area', $aktiv->building_area) }}">
                    @error('building_area')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <label for="gas">Газ</label>
                <select class="form-control form-select mb-3" name="gas" id="gas">
                    <option value="Мавжуд" {{ old('gas', $aktiv->gas) == 'Мавжуд' ? 'selected' : '' }}>Мавжуд</option>
                    <option value="Мавжуд эмас" {{ old('gas', $aktiv->gas) == 'Мавжуд эмас' ? 'selected' : '' }}>Мавжуд
                        эмас</option>
                </select>
                @error('gas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <label for="water">Сув</label>
                <select class="form-control form-select mb-3" name="water" id="water">
                    <option value="Мавжуд" {{ old('water', $aktiv->water) == 'Мавжуд' ? 'selected' : '' }}>Мавжуд</option>
                    <option value="Мавжуд эмас" {{ old('water', $aktiv->water) == 'Мавжуд эмас' ? 'selected' : '' }}>
                        Мавжуд
                        эмас</option>
                </select>
                @error('water')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <label for="electricity">Электр</label>
                <select class="form-control form-select mb-3" name="electricity" id="electricity">
                    <option value="Мавжуд" {{ old('electricity', $aktiv->electricity) == 'Мавжуд' ? 'selected' : '' }}>
                        Мавжуд</option>
                    <option value="Мавжуд эмас"
                        {{ old('electricity', $aktiv->electricity) == 'Мавжуд эмас' ? 'selected' : '' }}>Мавжуд эмас
                    </option>
                </select>
                @error('electricity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label for="additional_info">Қўшимча маълумот</label>
                    <input class="form-control" type="text" name="additional_info" id="additional_info"
                        value="{{ old('additional_info', $aktiv->additional_info) }}">
                    @error('additional_info')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>



                <!-- Region Information -->
                <div class="mb-3">
                    <strong>Вилоят номи (Region Name):</strong>
                    {{ $aktiv->subStreet->district->region->name_uz ?? 'Маълумот йўқ' }}
                </div>

                <!-- District Information -->
                <div class="mb-3">
                    <strong>Туман номи (District Name):</strong>
                    {{ $aktiv->subStreet->district->name_uz ?? 'Маълумот йўқ' }}
                </div>
                <!-- SubStreet Information -->
                <div class="mb-3">
                    <strong>Кўча номи (Sub Street Name):</strong>
                    {{ $aktiv->subStreet->name ?? 'Маълумот йўқ' }}
                </div>

                <!-- Include Address Partial -->
                @include('inc.__address')

            </div>
            <!-- Right Column -->
            <div class="col-md-6">
                <!-- Existing Files -->
                <div class="mb-3">
                    <label class="text-primary">Мавжуд файллар</label>
                    <div id="existing-files" class="mb-3">
                        @foreach ($aktiv->files as $file)
                            <div class="existing-file mb-2">
                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank">Файлни кўриш</a>
                                <label>
                                    <input type="checkbox" name="delete_files[]" value="{{ $file->id }}">
                                    Ўчириш
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- File upload fields -->
                <div class="mb-3">
                    <label class="text-danger">Янги файлларни юклаш (Камида 4 та файл бўлиши шарт)</label>
                </div>
                <!-- Error message display -->
                <div id="file-error" class="text-danger mb-3"></div>

                <!-- Container to hold new file inputs -->
                <div id="file-upload-container">
                    <div class="mb-3">
                        <label for="file1">Биринчи файл</label>
                        <input type="file" class="form-control" name="files[]" id="file1">
                    </div>
                    <div class="mb-3">
                        <label for="file2">Иккинчи файл</label>
                        <input type="file" class="form-control" name="files[]" id="file2">
                    </div>
                    <div class="mb-3">
                        <label for="file3">Учинчи файл</label>
                        <input type="file" class="form-control" name="files[]" id="file3">
                    </div>
                    <div class="mb-3">
                        <label for="file4">Тўртинчи файл</label>
                        <input type="file" class="form-control" name="files[]" id="file4">
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mb-3" onclick="addFileInput()">Янги файл қўшиш</button>

                <!-- Map Section -->
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

                <!-- Hidden Fields for Coordinates -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $aktiv->latitude) }}">
                <input type="hidden" name="longitude" id="longitude"
                    value="{{ old('longitude', $aktiv->longitude) }}">

                <!-- Geolocation URL Field -->
                <div class="mb-3">
                    <label for="geolokatsiya">Геолокация (координата)</label>
                    <input class="form-control" type="text" name="geolokatsiya" id="geolokatsiya" readonly required
                        value="{{ old('geolokatsiya', $aktiv->geolokatsiya) }}">
                    @error('geolokatsiya')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success" id="submit-btn">Сақлаш</button>
    </form>
@endsection

@section('scripts')
    <!-- Include Google Maps script and initialization code -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAnUwWTguBMsDU8UrQ7Re-caVeYCmcHQY&libraries=geometry">
    </script>
    <!-- Place the JavaScript code at the end, inside the 'scripts' section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // JavaScript code goes here
            function validateFiles() {
                const submitBtn = document.getElementById('submit-btn');
                const errorDiv = document.getElementById('file-error');

                // Get all new file inputs
                const fileInputs = document.querySelectorAll('input[type="file"][name="files[]"]');

                let totalFiles = 0;
                fileInputs.forEach(input => {
                    totalFiles += input.files.length;
                });

                // Get the count of existing files not marked for deletion
                const existingFiles = document.querySelectorAll('#existing-files .existing-file');
                const deleteCheckboxes = document.querySelectorAll(
                    'input[type="checkbox"][name="delete_files[]"]:checked');
                const existingFilesCount = existingFiles.length - deleteCheckboxes.length;

                const totalFileCount = totalFiles + existingFilesCount;

                // Validate minimum file requirement
                if (totalFileCount < 4) {
                    let filesNeeded = 4 - totalFileCount;
                    if (totalFileCount === 0) {
                        errorDiv.textContent = 'Сиз ҳеч қандай файл мавжуд эмас. Илтимос, камида 4 та файл юкланг.';
                    } else {
                        errorDiv.textContent = 'Сиз яна ' + filesNeeded + ' та файл қўшишингиз керак.';
                    }
                    submitBtn.disabled = true;
                } else {
                    errorDiv.textContent = '';
                    submitBtn.disabled = false;
                }
            }

            function addFileInput() {
                const container = document.getElementById('file-upload-container');
                const fileInputCount = container.querySelectorAll('input[type="file"]').length + 1;
                const newDiv = document.createElement('div');
                newDiv.classList.add('mb-3');
                const label = document.createElement('label');
                label.textContent = 'Қўшимча файл ' + fileInputCount;
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('name', 'files[]');
                input.setAttribute('class', 'form-control');
                input.addEventListener('change', validateFiles);
                newDiv.appendChild(label);
                newDiv.appendChild(input);
                container.appendChild(newDiv);
            }

            // Disable submit button initially
            document.getElementById('submit-btn').disabled = false; // Allow initial load if existing files >= 4

            // Add event listeners to initial file inputs
            document.getElementById('file1').addEventListener('change', validateFiles);
            document.getElementById('file2').addEventListener('change', validateFiles);
            document.getElementById('file3').addEventListener('change', validateFiles);
            document.getElementById('file4').addEventListener('change', validateFiles);

            // Add event listener to delete checkboxes
            const deleteCheckboxes = document.querySelectorAll('input[type="checkbox"][name="delete_files[]"]');
            deleteCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', validateFiles);
            });

            // Initial validation
            validateFiles();

            // Form submission handling
            document.getElementById('aktiv-form').addEventListener('submit', function(event) {
                // Re-validate files on submit
                validateFiles();

                // If the submit button is disabled, prevent form submission
                if (document.getElementById('submit-btn').disabled) {
                    event.preventDefault();
                } else {
                    document.getElementById('submit-btn').disabled = true;
                    document.getElementById('submit-btn').innerText = 'Юкланмоқда...';
                }
            });

            // Google Maps initialization
            let map;
            let marker;

            function initMap() {
                const mapOptions = {
                    center: {
                        lat: parseFloat(document.getElementById('latitude').value) || 41.2995,
                        lng: parseFloat(document.getElementById('longitude').value) || 69.2401
                    },
                    zoom: 10,
                };

                map = new google.maps.Map(document.getElementById('map'), mapOptions);

                if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
                    const position = {
                        lat: parseFloat(document.getElementById('latitude').value),
                        lng: parseFloat(document.getElementById('longitude').value)
                    };
                    placeMarker(position);
                    map.setCenter(position);
                    map.setZoom(15);
                }

                document.getElementById('find-my-location').addEventListener('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const userLocation = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };

                                map.setCenter(userLocation);
                                map.setZoom(15);
                                placeMarker(userLocation);

                                // Set latitude, longitude, and geolocation URL in the input fields
                                document.getElementById('latitude').value = userLocation.lat;
                                document.getElementById('longitude').value = userLocation.lng;
                                document.getElementById('geolokatsiya').value =
                                    `https://www.google.com/maps?q=${userLocation.lat},${userLocation.lng}`;
                            },
                            function(error) {
                                console.error('Error occurred. Error code: ' + error.code);
                                alert('Жойлашувингиз аниқланмади: ' + error.message);
                            }
                        );
                    } else {
                        alert('Жойлашувни аниқлаш браузерингиз томонидан қўлланилмайди.');
                    }
                });

                map.addListener('click', function(event) {
                    placeMarker(event.latLng);
                });
            }

            function placeMarker(location) {
                if (marker) {
                    marker.setMap(null);
                }

                marker = new google.maps.Marker({
                    position: location,
                    map: map
                });

                const lat = typeof location.lat === "function" ? location.lat() : location.lat;
                const lng = typeof location.lng === "function" ? location.lng() : location.lng;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('geolokatsiya').value = `https://www.google.com/maps?q=${lat},${lng}`;
            }

            // Initialize the map after the page has loaded
            initMap();
        });
    </script>
@endsection
