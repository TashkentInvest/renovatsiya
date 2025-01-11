@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Объект маълумотлари (Детали объекта)</h1>


    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
        }

        .info-section {
            background-color: #f1f3f5;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .info-section strong {
            font-size: 1rem;
            color: #495057;
        }

        .info-section p {
            margin: 0;
            color: #6c757d;
        }

        .file-section a {
            text-decoration: none;
        }
    </style>


    <div class="row">
        <div class="col-12 col-md-12 col-lg-6">
            <!-- General Information -->
            <div class="card shadow-sm p-4 mb-4 border-primary">
                <h5 class="card-title text-primary font-weight-bold mb-3">Умумий маълумотлар</h5>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Объект номи (Название объекта):</strong>
                    <p class="text-muted">{{ $aktiv->object_name ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Балансда сақловчи (Балансодержатель):</strong>
                    <p class="text-muted">{{ $aktiv->balance_keeper ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Мўлжал (Местоположение):</strong>
                    <p class="text-muted">{{ $aktiv->location ?? 'Мавжуд Эмас' }}</p>
                </div>
            </div>

            <!-- Location Information -->
            <div class="card shadow-sm p-4 mb-4 border-primary">
                <h5 class="card-title text-primary font-weight-bold mb-3">Жойлашув</h5>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Вилоят номи:</strong>
                    <p class="text-muted">{{ $aktiv->subStreet->district->region->name_uz ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Туман номи:</strong>
                    <p class="text-muted">{{ $aktiv->subStreet->district->name_uz ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Мфй номи:</strong>
                    <p class="text-muted">{{ $aktiv->street->name ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Кўча номи:</strong>
                    <p class="text-muted">{{ $aktiv->subStreet->name ?? 'Мавжуд Эмас' }}</p>
                </div>
            </div>

            <!-- Technical Information -->
            <div class="card shadow-sm p-4 mb-4 border-primary">
                <h5 class="card-title text-primary font-weight-bold mb-3">Техник маълумотлар</h5>
             
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Ер майдони (Площадь земли) (кв.м):</strong>
                    <p class="text-muted">{{ $aktiv->land_area ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Бино майдони (Площадь здания) (кв.м):</strong>
                    <p class="text-muted">{{ $aktiv->building_area ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Газ (Газ):</strong>
                    <p class="text-muted">{{ $aktiv->gas ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Сув (Вода):</strong>
                    <p class="text-muted">{{ $aktiv->water ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Электр (Электричество):</strong>
                    <p class="text-muted">{{ $aktiv->electricity ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Қўшимча маълумот (Дополнительная информация):</strong>
                    <p class="text-muted">{{ $aktiv->additional_info ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Кадастр рақами (Кадастровый номер):</strong>
                    <p class="text-muted">{{ $aktiv->kadastr_raqami ?? 'Мавжуд Эмас' }}</p>
                </div>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Геолокация (Ссылка на геолокацию):</strong>
                    <a href="{{ $aktiv->geolokatsiya ?? 'Мавжуд Эмас' }}" target="_blank"
                        class="btn btn-outline-primary btn-sm">
                        {{ $aktiv->geolokatsiya ?? 'Мавжуд Эмас' }}
                    </a>
                </div>
            </div>
        </div>


        <div class="col-12 col-md-12 col-lg-6">
            <!-- Additional New Fields (Новые поля) -->
            <div class="card shadow-sm p-4 mb-4 border-primary">
                <h5 class="card-title text-primary font-weight-bold mb-3">Янги майдонлар</h5>
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Умумий майдон (Площадь) (кв.м):</strong>
                    <p class="text-muted">{{ $aktiv->total_area ?? 'Мавжуд Эмас' }}</p>
                </div>
                <!-- 1) Турар жой майдони -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Турар жой майдони:</strong>
                    <p class="text-muted">{{ $aktiv->turar_joy_maydoni ?? 'Мавжуд Эмас' }} kv.m</p>
                </div>

                <!-- 2) Нотурар жой майдони -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Нотурар жой майдони:</strong>
                    <p class="text-muted">{{ $aktiv->noturar_joy_maydoni ?? 'Мавжуд Эмас' }} kv.m</p>
                </div>

                <!-- 3) Вақтинчалик тўхташ жойи маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Вақтинчалик тўхташ жойи (Парковка) маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->vaqtinchalik_parking_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 4) Доимий тўхташ жойи маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Доимий тўхташ жойи (Парковка) маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->doimiy_parking_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 5) Мактабгача таълим ташкилоти маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Мактабгача таълим ташкилоти маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->maktabgacha_tashkilot_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 6) Умумтаълим мактаби маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Умумтаълим мактаби маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->umumtaolim_maktab_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 7) Стационар тиббиёт муассасаси маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Стационар тиббиёт муассасаси маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->stasionar_tibbiyot_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 8) Амбулатор тиббиёт муассасаси маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Амбулатор тиббиёт муассасаси маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->ambulator_tibbiyot_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 9) Диний муассасаси маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Диний муассасаси маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->diniy_muassasa_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 10) Спорт-соғломлаштириш муассасаси маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Спорт-соғломлаштириш муассасаси маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->sport_soglomlashtirish_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 11) Сақланадиган кўкаламзорлаштириш маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Сақланадиган кўкаламзорлаштириш маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->saqlanadigan_kokalamzor_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 12) Янгидан ташкил қилинадиган кўкаламзорлаштириш маълумот -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Янгидан ташкил қилинадиган кўкаламзорлаштириш
                        маълумот:</strong>
                    <p class="text-muted">{{ $aktiv->yangidan_tashkil_kokalamzor_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 13) Сақланадиган муҳандислик-коммуникация тармоқлари -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Сақланадиган муҳандислик-коммуникация тармоқлари:</strong>
                    <p class="text-muted">{{ $aktiv->saqlanadigan_muhandislik_tarmoqlari_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 14) Янгидан қуриладиган муҳандислик-коммуникация тармоқлари -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Янгидан қуриладиган муҳандислик-коммуникация
                        тармоқлари:</strong>
                    <p class="text-muted">{{ $aktiv->yangidan_quriladigan_muhandislik_tarmoqlari_info ?? 'Мавжуд Эмас' }}
                    </p>
                </div>

                <!-- 15) Сақланадиган йўллар ва йўлаклар -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Сақланадиган йўллар ва йўлаклар:</strong>
                    <p class="text-muted">{{ $aktiv->saqlanadigan_yollar_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- 16) Янгидан қуриладиган йўллар ва йўлаклар -->
                <div class="info-section">
                    <strong class="text-dark font-weight-bold">Янгидан қуриладиган йўллар ва йўлаклар:</strong>
                    <p class="text-muted">{{ $aktiv->yangidan_quriladigan_yollar_info ?? 'Мавжуд Эмас' }}</p>
                </div>

                <!-- File Attachments Section -->
                <div class="d-flex" style="flex-wrap: wrap">
                    @foreach ($aktiv->docs as $doc)
                        <div class="mb-3 mx-3">
                            <label class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $doc->doc_type)) }}</label>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    {{ $doc->path ? 'Файлни Кориш' : '' }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <!-- Display Files -->
    <div class="card shadow-sm p-4 mb-4">
        <h5 class="card-title text-primary">Юкланган файллар (Загруженные файлы)</h5>
        @if ($aktiv->files->count())
            <!-- Swiper Container -->
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($aktiv->files as $file)
                        <div class="swiper-slide">
                            @if (strtolower(pathinfo($file->path, PATHINFO_EXTENSION)) === 'heic')
                                <!-- HEIC images will be converted using HEIC2ANY -->
                                <img data-heic="{{ asset('storage/' . $file->path) }}" class="heic-image"
                                    alt="Image">
                            @else
                                <!-- Display non-HEIC images directly -->
                                <a href="{{ asset('storage/' . $file->path) }}" class="glightbox"
                                    data-gallery="aktiv-gallery" data-title="{{ $aktiv->object_name }}"
                                    data-description="{{ $aktiv->additional_info }}">
                                    <img src="{{ asset('storage/' . $file->path) }}" alt="Image">
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
                <!-- Add Pagination and Navigation -->
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @else
            <p class="text-muted">Файллар мавжуд эмас (Нет загруженных файлов).</p>
        @endif
    </div>

    <!-- Map Section -->
    <div class="card shadow-sm p-4 mb-4">
        <h5 class="card-title text-primary">Геолокация на карте</h5>
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('aktivs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Рўйхатга қайтиш (Вернуться к списку)
        </a>
        @if (auth()->user()->roles[0]->name != 'Manager')
            <a href="{{ route('aktivs.edit', $aktiv->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Объектни таҳрирлаш (Редактировать объект)
            </a>
        @endif
    </div>
@endsection

@section('styles')
    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">

    <style>
        .btn-secondary,
        .btn-primary {
            transition: background-color 0.2s ease, transform 0.2s;
        }

        .btn-secondary:hover,
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        /* Swiper Styles */
        .swiper-container {
            width: 100%;
            padding-bottom: 50px;
        }

        .swiper-slide {
            /* Adjust the width to mimic col-3 (25%) or col-6 (50%) */
            width: 25%;
            /* For col-3 */
            /* width: 50%; */
            /* For col-6 */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .swiper-slide {
                width: 50%;
                /* Show 2 slides per view on smaller screens */
            }
        }

        @media (max-width: 576px) {
            .swiper-slide {
                width: 100%;
                /* Show 1 slide per view on extra small screens */
            }
        }
    </style>
@endsection

@section('scripts')
    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <!-- HEIC2ANY JS for HEIC image conversion -->
    <script src="https://cdn.jsdelivr.net/npm/heic2any/dist/heic2any.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper
            const swiper = new Swiper('.swiper-container', {
                loop: false,
                slidesPerView: 4, // For col-3 equivalent (4 slides per view)
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                // Responsive breakpoints
                breakpoints: {
                    768: {
                        slidesPerView: 2, // For tablet devices
                    },
                    576: {
                        slidesPerView: 1, // For mobile devices
                    },
                },
            });

            // Convert and display HEIC images
            document.querySelectorAll('.heic-image').forEach(async (img) => {
                const heicUrl = img.getAttribute('data-heic');
                try {
                    const response = await fetch(heicUrl);
                    const blob = await response.blob();
                    const convertedImage = await heic2any({
                        blob,
                        toType: 'image/jpeg',
                    });
                    img.src = URL.createObjectURL(convertedImage);
                } catch (error) {
                    console.error('Error converting HEIC image:', error);
                }
            });

            // Initialize GLightbox
            const lightbox = GLightbox({
                selector: '.glightbox',
                loop: true,
            });

            // Map Initialization
            const currentAktiv = @json($aktiv);
            const aktivs = @json($aktivs);
            const defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

            let map;
            let infoWindow;

            function initMap() {
                const aktivLatitude = parseFloat(currentAktiv.latitude);
                const aktivLongitude = parseFloat(currentAktiv.longitude);

                const mapOptions = {
                    center: {
                        lat: aktivLatitude,
                        lng: aktivLongitude
                    },
                    zoom: 15,
                };

                map = new google.maps.Map(document.getElementById('map'), mapOptions);
                infoWindow = new google.maps.InfoWindow();

                const currentAktivPosition = {
                    lat: aktivLatitude,
                    lng: aktivLongitude
                };

                const currentAktivMarker = new google.maps.Marker({
                    position: currentAktivPosition,
                    map: map,
                    title: currentAktiv.object_name,
                    icon: {
                        url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
                        scaledSize: new google.maps.Size(50, 50)
                    }
                });

                currentAktivMarker.addListener('click', function() {
                    openInfoWindow(currentAktiv, currentAktivMarker);
                });

                aktivs.forEach(function(a) {
                    if (a.latitude && a.longitude && a.id !== currentAktiv.id) {
                        const position = {
                            lat: parseFloat(a.latitude),
                            lng: parseFloat(a.longitude)
                        };

                        const aktivMarker = new google.maps.Marker({
                            position: position,
                            map: map,
                            title: a.object_name,
                            icon: 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
                        });

                        aktivMarker.addListener('click', function() {
                            openInfoWindow(a, aktivMarker);
                        });
                    }
                });
            }

            function openInfoWindow(aktiv, marker) {
                const mainImagePath = aktiv.files && aktiv.files.length > 0 ?
                    `/storage/${aktiv.files[0].path}` : defaultImage;

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
                        <p><strong>Қарта:</strong> <a href="${aktiv.geolokatsiya || '#'}" target="_blank">${aktiv.geolokatsiya || 'N/A'}</a></p>
                    </div>
                `;

                infoWindow.setContent(contentString);
                infoWindow.open(map, marker);
            }

            // Initialize the map
            initMap();
        });
    </script>

    <!-- Include the Google Maps JavaScript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAnUwWTguBMsDU8UrQ7Re-caVeYCmcHQY&libraries=geometry">
    </script>
@endsection
