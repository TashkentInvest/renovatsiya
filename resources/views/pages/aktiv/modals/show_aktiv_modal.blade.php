<!-- Show Aktiv Modal -->
<div class="modal fade" id="showAktivModal" tabindex="-1" aria-labelledby="showAktivModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="showAktivModalLabel">
                    <i class="fas fa-building me-2"></i> Актив маълумотлари
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Ёпиш"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Асосий маълумотлар -->
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 bg-light">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Асосий маълумотлар</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i class="fas fa-map-marker-alt me-1"></i>
                                                Туман номи:</label>
                                            <p class="ms-4 mb-0" id="district_name">Юкланмоқда...</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i class="fas fa-home me-1"></i> Маҳалла
                                                номи:</label>
                                            <p class="ms-4 mb-0" id="neighborhood_name">Юкланмоқда...</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i class="fas fa-road me-1"></i>
                                                Кўча:</label>
                                            <p class="ms-4 mb-0" id="street_name">Юкланмоқда...</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i class="fas fa-street-view me-1"></i>
                                                Даҳа:</label>
                                            <p class="ms-4 mb-0" id="sub_street_name">Юкланмоқда...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Майдон маълумотлари -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-success bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i> Майдон маълумотлари</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-globe me-1"></i> Ҳудуд майдони
                                        (гектар):</label>
                                    <p class="ms-4 mb-0" id="area_hectare">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-building me-1"></i> Қурилиш ости
                                        майдони жами (м²):</label>
                                    <p class="ms-4 mb-0" id="total_building_area">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-home me-1"></i> Турар жой майдони
                                        (м²):</label>
                                    <p class="ms-4 mb-0" id="residential_area">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-store me-1"></i> Нотурар жой
                                        майдони (м²):</label>
                                    <p class="ms-4 mb-0" id="non_residential_area">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-object-group me-1"></i> Туташ
                                        ҳудуд:</label>
                                    <p class="ms-4 mb-0" id="adjacent_area">Юкланмоқда...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Қурилиш маълумотлари -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-hard-hat me-2"></i> Қурилиш маълумотлари</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-percentage me-1"></i> УМН
                                        коэффициенти:</label>
                                    <p class="ms-4 mb-0" id="umn_coefficient">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-percentage me-1"></i> ҚМН
                                        фоизи:</label>
                                    <p class="ms-4 mb-0" id="qmn_percentage">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-building me-1"></i> Белгиланган
                                        қаватлар:</label>
                                    <p class="ms-4 mb-0" id="designated_floors">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-building me-1"></i> Таклиф
                                        этилган қаватлар:</label>
                                    <p class="ms-4 mb-0" id="proposed_floors">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-id-card me-1"></i> Кадастр
                                        далолатномаси:</label>
                                    <p class="ms-4 mb-0" id="cadastre_certificate">Юкланмоқда...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Қўшимча маълумотлар -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-info bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Қўшимча маълумотлар</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-gavel me-1"></i> Қарор
                                        рақами:</label>
                                    <p class="ms-4 mb-0" id="decision_number">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-briefcase me-1"></i>
                                        Инвестор:</label>
                                    <p class="ms-4 mb-0" id="investor">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-tasks me-1"></i>
                                        Статус:</label>
                                    <p class="ms-4 mb-0" id="status">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-flag me-1"></i> Ҳудуд
                                        стратегияси:</label>
                                    <p class="ms-4 mb-0" id="area_strategy">Юкланмоқда...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Аҳоли маълумотлари -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-danger bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Аҳоли маълумотлари</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-users me-1"></i> Аҳоли
                                        сони:</label>
                                    <p class="ms-4 mb-0" id="population">Юкланмоқда...</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-home me-1"></i> Хонадон
                                        сони:</label>
                                    <p class="ms-4 mb-0" id="household_count">Юкланмоқда...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Объект ҳақида маълумот -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-secondary bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Объект ҳақида маълумот</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-clipboard-list me-1"></i>
                                        Ҳудуддаги объектлар тўғрисида маълумот:</label>
                                    <div class="p-3 bg-light rounded mt-2" id="object_information">Юкланмоқда...</div>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted"><i class="fas fa-plus-circle me-1"></i> Қўшимча
                                        маълумот:</label>
                                    <div class="p-3 bg-light rounded mt-2" id="additional_information">Юкланмоқда...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Координаталар -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Координаталар</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i class="fas fa-map-pin me-1"></i>
                                                Латитуда:</label>
                                            <p class="ms-4 mb-0" id="latitude">Юкланмоқда...</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i class="fas fa-map-pin me-1"></i>
                                                Лонгитуда:</label>
                                            <p class="ms-4 mb-0" id="longitude">Юкланмоқда...</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i
                                                    class="fas fa-flag-checkered me-1"></i> Бошланғич
                                                координаталар:</label>
                                            <p class="ms-4 mb-0">
                                                <span id="start_lat">Юкланмоқда...</span>,
                                                <span id="start_lon">Юкланмоқда...</span>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted"><i
                                                    class="fas fa-flag-checkered me-1"></i> Якуний
                                                координаталар:</label>
                                            <p class="ms-4 mb-0">
                                                <span id="end_lat">Юкланмоқда...</span>,
                                                <span id="end_lon">Юкланмоқда...</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Ёпиш
                </button>
                <a href="#" id="editAktivBtn" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Таҳрирлаш
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Aktiv Modal Functions
    function showAktivModal(id) {
        // Show loading state
        document.querySelectorAll('#showAktivModal [id]').forEach(el => {
            if (el.id !== 'showAktivModalLabel' && el.id !== 'editAktivBtn') {
                el.innerHTML = '<div class="placeholder-glow"><span class="placeholder col-12"></span></div>';
            }
        });

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('showAktivModal'));
        modal.show();

        // Set the edit button URL
        document.getElementById('editAktivBtn').href = `/aktivs/${id}/edit`;

        // Fetch the aktiv data
        fetch(`/aktivs/${id}/get-data`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Маълумот олишда хатолик юз берди');
                }
                return response.json();
            })
            .then(data => {
                // Fill the modal with data
                populateModalWithData(data);
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message in modal
                document.querySelectorAll('#showAktivModal [id]').forEach(el => {
                    if (el.id !== 'showAktivModalLabel' && el.id !== 'editAktivBtn') {
                        el.innerHTML = '<span class="text-danger">Маълумот олишда хатолик юз берди</span>';
                    }
                });
            });
    }

    function populateModalWithData(data) {
        // Basic information
        setValue('district_name', data.district_name || 'Кўрсатилмаган');
        setValue('neighborhood_name', data.neighborhood_name || 'Кўрсатилмаган');
        setValue('street_name', data.street ? data.street.name : 'Кўрсатилмаган');
        setValue('sub_street_name', data.sub_street ? data.sub_street.name : 'Кўрсатилмаган');

        // Area information
        setValue('area_hectare', formatNumber(data.area_hectare) + ' гектар' || 'Кўрсатилмаган');
        setValue('total_building_area', formatNumber(data.total_building_area) + ' м²' || 'Кўрсатилмаган');
        setValue('residential_area', formatNumber(data.residential_area) + ' м²' || 'Кўрсатилмаган');
        setValue('non_residential_area', formatNumber(data.non_residential_area) + ' м²' || 'Кўрсатилмаган');
        setValue('adjacent_area', formatNumber(data.adjacent_area) + ' м²' || 'Кўрсатилмаган');

        // Construction information
        setValue('umn_coefficient', data.umn_coefficient || 'Кўрсатилмаган');
        setValue('qmn_percentage', data.qmn_percentage || 'Кўрсатилмаган');
        setValue('designated_floors', data.designated_floors || 'Кўрсатилмаган');
        setValue('proposed_floors', data.proposed_floors || 'Кўрсатилмаган');
        setValue('cadastre_certificate', data.cadastre_certificate || 'Кўрсатилмаган');

        // Additional information
        setValue('decision_number', data.decision_number || 'Кўрсатилмаган');
        setValue('investor', data.investor || 'Кўрсатилмаган');
        setValue('status', data.status || 'Кўрсатилмаган');
        setValue('area_strategy', data.area_strategy || 'Кўрсатилмаган');

        // Population information
        setValue('population', formatNumber(data.population) || 'Кўрсатилмаган');
        setValue('household_count', formatNumber(data.household_count) || 'Кўрсатилмаган');

        // Object information
        setValue('object_information', data.object_information || 'Кўрсатилмаган');
        setValue('additional_information', data.additional_information || 'Кўрсатилмаган');

        // Coordinates
        setValue('latitude', data.latitude || 'Кўрсатилмаган');
        setValue('longitude', data.longitude || 'Кўрсатилмаган');
        setValue('start_lat', data.start_lat || 'Кўрсатилмаган');
        setValue('start_lon', data.start_lon || 'Кўрсатилмаган');
        setValue('end_lat', data.end_lat || 'Кўрсатилмаган');
        setValue('end_lon', data.end_lon || 'Кўрсатилмаган');
    }

    function setValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    }

    function formatNumber(num) {
        if (!num) return '';
        return new Intl.NumberFormat('uz-UZ').format(num);
    }
</script>

<style>
    /* Modal Styles for Aktivs */
    #showAktivModal .modal-content {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    #showAktivModal .modal-header {
        background: linear-gradient(45deg, #1976d2, #2196f3);
        border-bottom: none;
        padding: 1.25rem;
    }

    #showAktivModal .modal-title {
        font-family: 'Roboto', sans-serif;
        letter-spacing: 0.5px;
    }

    #showAktivModal .card {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    #showAktivModal .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    #showAktivModal .card-header {
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        border-bottom: none;
    }

    #showAktivModal .fw-bold.text-muted {
        color: #546e7a !important;
        font-size: 0.9rem;
    }

    #showAktivModal p {
        font-size: 1rem;
        color: #263238;
    }

    #showAktivModal .ms-4 {
        font-weight: 500;
        border-left: 3px solid #2196f3;
        padding-left: 0.75rem;
        margin-top: 0.5rem;
    }

    #showAktivModal .modal-footer {
        border-top: none;
        padding: 1.25rem;
    }

    #showAktivModal .btn {
        border-radius: 0.25rem;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    #showAktivModal .bg-light {
        background-color: #f5f7fa !important;
    }

    #showAktivModal .bg-primary.bg-opacity-10 {
        background-color: rgba(33, 150, 243, 0.1) !important;
    }

    #showAktivModal .bg-success.bg-opacity-10 {
        background-color: rgba(76, 175, 80, 0.1) !important;
    }

    #showAktivModal .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 152, 0, 0.1) !important;
    }

    #showAktivModal .bg-info.bg-opacity-10 {
        background-color: rgba(0, 188, 212, 0.1) !important;
    }

    #showAktivModal .bg-danger.bg-opacity-10 {
        background-color: rgba(244, 67, 54, 0.1) !important;
    }

    #showAktivModal .bg-secondary.bg-opacity-10 {
        background-color: rgba(96, 125, 139, 0.1) !important;
    }

    /* Placeholder loading animation */
    .placeholder-glow .placeholder {
        animation: placeholder-wave 2s ease-in-out infinite;
        background-color: rgba(33, 150, 243, 0.1);
    }
</style>
