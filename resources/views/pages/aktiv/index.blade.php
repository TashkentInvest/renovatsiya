@extends('layouts.admin')

@section('title', 'Активлар рўйхати')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom colors and styles */
        .bg-brandHighlight-500 {
            background-color: #23ADE8;
        }

        .bg-brandHighlight-700 {
            background-color: #0c8abb;
        }

        .bg-brandHighlight-100 {
            background-color: #e6f4fa;
        }

        /* Icon backgrounds */
        .icon-bg-blue {
            background-color: #23ADE8;
        }

        .icon-bg-indigo {
            background-color: #556EE6;
        }

        .icon-bg-orange {
            background-color: #E69B55;
        }

        .icon-bg-red {
            background-color: #E65555;
        }

        .icon-bg-green {
            background-color: #34D399;
        }

        /* Card hover effects */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Table styles */
        .table-header {
            background: linear-gradient(90deg, #f8f9fa, #e9ecef);
        }

        .aktiv-row {
            transition: all 0.2s ease;
        }

        .aktiv-row:hover {
            background-color: rgba(35, 173, 232, 0.05);
        }

        /* Form elements */
        .form-control:focus,
        .form-select:focus {
            border-color: #23ADE8;
            box-shadow: 0 0 0 0.25rem rgba(35, 173, 232, 0.25);
        }

        /* Beautiful scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #23ADE8;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #0c8abb;
        }

        /* Pagination styling */
        .pagination {
            gap: 5px;
        }

        .page-item.active .page-link {
            background-color: #23ADE8;
            border-color: #23ADE8;
        }

        .page-link {
            color: #23ADE8;
            border-radius: 5px;
        }

        .page-link:hover {
            color: #0c8abb;
            background-color: #e6f4fa;
        }

        /* Status indicators */
        .status-badge {
            padding: 0.35em 0.65em;
            border-radius: 50rem;
            font-size: 0.75em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Loading skeleton effect */
        @keyframes skeleton-loading {
            0% {
                background-position: -200px 0;
            }

            100% {
                background-position: calc(200px + 100%) 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200px 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <!-- Header with stats -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-0 fw-bold text-dark">Активлар рўйхати</h1>
                            <p class="text-muted mb-0">Жами: {{ number_format($aktivs->total()) }} та актив</p>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-0">Сана: <span
                                    class="fw-semibold">{{ now()->format('Y-m-d H:i:s') }}</span></p>
                            <p class="text-muted mb-0">Фойдаланувчи: <span
                                    class="fw-semibold">{{ Auth::user()->name }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-12">
                <ul
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 px-0 justify-center items-center list-unstyled row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-6 g-4">
                    <!-- Card 1: Total Aktivs -->
                    <li class="col">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 card-hover">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-6">Жами активлар</span>
                                    <span class="fw-bold fs-4">{{ number_format($aktivs->total()) }}</span>
                                </div>
                                <div
                                    class="rounded-circle p-3 bg-brandHighlight-500 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-layer-group text-white"></i>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Card 2: Residential Areas -->
                    <li class="col">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 card-hover">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-6">Турар жой майдони</span>
                                    <span class="fw-bold fs-4">{{ number_format($aktivs->sum('residential_area'), 2) }}
                                        м²</span>
                                </div>
                                <div
                                    class="rounded-circle p-3 icon-bg-indigo d-flex align-items-center justify-content-center">
                                    <i class="fas fa-home text-white"></i>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Card 3: Non-Residential Areas -->
                    <li class="col">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 card-hover">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-6">Нотурар жой майдони</span>
                                    <span class="fw-bold fs-4">{{ number_format($aktivs->sum('non_residential_area'), 2) }}
                                        м²</span>
                                </div>
                                <div
                                    class="rounded-circle p-3 icon-bg-orange d-flex align-items-center justify-content-center">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Card 4: Total Building Area -->
                    <li class="col">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 card-hover">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-6">Қурилиш майдони</span>
                                    <span class="fw-bold fs-4">{{ number_format($aktivs->sum('total_building_area'), 2) }}
                                        м²</span>
                                </div>
                                <div
                                    class="rounded-circle p-3 icon-bg-red d-flex align-items-center justify-content-center">
                                    <i class="fas fa-ruler-combined text-white"></i>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Card 5: Population -->
                    <li class="col">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 card-hover">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center h-100">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-6">Аҳоли сони</span>
                                    <span class="fw-bold fs-4">{{ number_format($aktivs->sum('population')) }}</span>
                                </div>
                                <div
                                    class="rounded-circle p-3 icon-bg-green d-flex align-items-center justify-content-center">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Card 6: Actions -->
                    <li class="col">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 card-hover">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center h-100">
                                <a href="{{ route('aktivs.create') }}"
                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Янги актив яратиш</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('aktivs.index') }}" method="GET" class="row g-3">
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Қидириш..."
                                    value="{{ request('search') }}">
                            </div>

                            @if (Auth::user()->hasRole(['Super Admin', 'Manager']))
                                <div class="col-md-2">
                                    <select name="user_id" class="form-select">
                                        <option value="">Фойдаланувчини танланг</option>
                                        @foreach (\App\Models\User::orderBy('name')->get() as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-md-2">
                                    <select name="district_id" class="form-select">
                                        <option value="">Туманни танланг</option>
                                        @foreach (\App\Models\District::orderBy('name_uz')->get() as $district)
                                            <option value="{{ $district->id }}"
                                                {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                {{ $district->name_uz }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            @endif

                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Ҳолатни танланг</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Актив
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Ноактив</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" placeholder="Санадан"
                                    value="{{ request('date_from') }}">
                            </div>

                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" placeholder="Санагача"
                                    value="{{ request('date_to') }}">
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-search"></i> Қидириш
                                </button>

                                <a href="{{ route('aktivs.index') }}"
                                    class="btn btn-outline-danger d-flex align-items-center gap-2">
                                    <i class="fas fa-times"></i> Тозалаш
                                </a>

                                <div class="ms-auto">
                                    <button type="button" class="btn btn-outline-success d-flex align-items-center gap-2"
                                        id="exportBtn">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table table-hover mb-0">
                                <thead class="table-header">
                                    <tr>
                                        <th class="fw-semibold text-dark px-3 py-3">№</th>
                                        <th class="fw-semibold text-dark px-3 py-3">Туман</th>
                                        <th class="fw-semibold text-dark px-3 py-3">Маҳалла</th>
                                        <th class="fw-semibold text-dark px-3 py-3">Ҳудуд майдони (га)</th>
                                        <th class="fw-semibold text-dark px-3 py-3">Қурилиш майдони (м²)</th>
                                        <th class="fw-semibold text-dark px-3 py-3">Инвестор</th>
                                        <th class="fw-semibold text-dark px-3 py-3">Ҳолат</th>
                                        <th class="fw-semibold text-dark px-3 py-3 text-center">Амаллар</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($aktivs as $key => $aktiv)
                                        <tr class="aktiv-row">
                                            <td class="px-3 py-3 align-middle">{{ $aktivs->firstItem() + $key }}</td>
                                            <td class="px-3 py-3 align-middle">
                                                {{ $aktiv->district_name ?? 'Кўрсатилмаган' }}</td>
                                            <td class="px-3 py-3 align-middle">
                                                {{ $aktiv->neighborhood_name ?? 'Кўрсатилмаган' }}</td>
                                            <td class="px-3 py-3 align-middle">
                                                {{ number_format($aktiv->area_hectare, 2) }}</td>
                                            <td class="px-3 py-3 align-middle">
                                                {{ number_format($aktiv->total_building_area, 2) }}</td>
                                            <td class="px-3 py-3 align-middle">{{ $aktiv->investor ?? 'Кўрсатилмаган' }}
                                            </td>
                                            <td class="px-3 py-3 align-middle">
                                                @if ($aktiv->status)
                                                    <span
                                                        class="status-badge bg-success text-white">{{ $aktiv->status }}</span>
                                                @else
                                                    <span class="status-badge bg-secondary text-white">Кўрсатилмаган</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 align-middle text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('aktivs.show', $aktiv) }}"
                                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                        title="Кўриш">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalAktiv{{ $aktiv->id }}">
                                                        <i class="fas fa-search-plus"></i>
                                                    </button>

                                                    @if (Auth::user()->id == $aktiv->user_id || Auth::user()->hasRole(['Super Admin', 'Manager']))
                                                        <a href="{{ route('aktivs.edit', $aktiv) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Таҳрирлаш">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('aktivs.destroy', $aktiv) }}"
                                                            method="POST" style="display:inline;"
                                                            onsubmit="return confirm('Сиз ростдан ҳам бу объектни ўчиришни истайсизми?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="tooltip" title="Ўчириш">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal for aktiv details -->
                                        <div class="modal fade" id="modalAktiv{{ $aktiv->id }}" tabindex="-1"
                                            aria-labelledby="modalLabel{{ $aktiv->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header bg-brandHighlight-500 text-white">
                                                        <h5 class="modal-title" id="modalLabel{{ $aktiv->id }}">
                                                            <i class="fas fa-info-circle me-2"></i> Актив маълумотлари
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="row g-4">
                                                            <!-- Main Info -->
                                                            <div class="col-12">
                                                                <div class="card bg-light border-0">
                                                                    <div class="card-body">
                                                                        <h6 class="fw-bold mb-3">Умумий маълумотлар</h6>
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-3">
                                                                                <p class="text-muted mb-1">Туман:</p>
                                                                                <p class="fw-semibold">
                                                                                    {{ $aktiv->district_name ?? 'Кўрсатилмаган' }}
                                                                                </p>
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <p class="text-muted mb-1">Маҳалла:</p>
                                                                                <p class="fw-semibold">
                                                                                    {{ $aktiv->neighborhood_name ?? 'Кўрсатилмаган' }}
                                                                                </p>
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <p class="text-muted mb-1">Ҳудуд майдони
                                                                                    (га):</p>
                                                                                <p class="fw-semibold">
                                                                                    {{ number_format($aktiv->area_hectare, 2) }}
                                                                                </p>
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <p class="text-muted mb-1">Қурилиш майдони
                                                                                    (м²):</p>
                                                                                <p class="fw-semibold">
                                                                                    {{ number_format($aktiv->total_building_area, 2) }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Areas Information -->
                                                            <div class="col-md-6">
                                                                <div class="card h-100 border-0 shadow-sm">
                                                                    <div class="card-body">
                                                                        <h6 class="fw-bold mb-3">Майдонлар</h6>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">Турар жой майдони
                                                                                (м²):</p>
                                                                            <p class="fw-semibold">
                                                                                {{ number_format($aktiv->residential_area, 2) }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">Нотурар жой майдони
                                                                                (м²):</p>
                                                                            <p class="fw-semibold">
                                                                                {{ number_format($aktiv->non_residential_area, 2) }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">Туташ ҳудуд (м²):
                                                                            </p>
                                                                            <p class="fw-semibold">
                                                                                {{ number_format($aktiv->adjacent_area, 2) }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Construction Information -->
                                                            <div class="col-md-6">
                                                                <div class="card h-100 border-0 shadow-sm">
                                                                    <div class="card-body">
                                                                        <h6 class="fw-bold mb-3">Қурилиш маълумотлари</h6>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">УМН коэффициент:</p>
                                                                            <p class="fw-semibold">
                                                                                {{ $aktiv->umn_coefficient ?? 'Кўрсатилмаган' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">ҚМН фоизи:</p>
                                                                            <p class="fw-semibold">
                                                                                {{ $aktiv->qmn_percentage ?? 'Кўрсатилмаган' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">Белгиланган
                                                                                қаватлар:</p>
                                                                            <p class="fw-semibold">
                                                                                {{ $aktiv->designated_floors ?? 'Кўрсатилмаган' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Additional Information -->
                                                            <div class="col-12">
                                                                <div class="card border-0 shadow-sm">
                                                                    <div class="card-body">
                                                                        <h6 class="fw-bold mb-3">Қўшимча маълумотлар</h6>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">Ҳудуддаги объектлар:
                                                                            </p>
                                                                            <div class="bg-light p-3 rounded">
                                                                                {!! $aktiv->object_information ?? 'Кўрсатилмаган' !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <p class="text-muted mb-1">Қўшимча маълумот:
                                                                            </p>
                                                                            <div class="bg-light p-3 rounded">
                                                                                {!! $aktiv->additional_information ?? 'Кўрсатилмаган' !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Ёпиш
                                                        </button>
                                                        <a href="{{ route('aktivs.show', $aktiv) }}"
                                                            class="btn btn-primary">
                                                            <i class="fas fa-eye me-1"></i> Батафсил кўриш
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-folder-open text-muted mb-2"
                                                        style="font-size: 3rem;"></i>
                                                    <p class="text-muted">Активлар топилмади</p>
                                                    <a href="{{ route('aktivs.create') }}"
                                                        class="btn btn-sm btn-primary mt-2">
                                                        <i class="fas fa-plus me-1"></i> Янги актив яратиш
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted">
                                Кўрсатилмоқда {{ $aktivs->firstItem() ?? 0 }} - {{ $aktivs->lastItem() ?? 0 }} из
                                {{ $aktivs->total() }}
                            </div>
                            <div>
                                {{ $aktivs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Excel export button functionality
            document.getElementById('exportBtn').addEventListener('click', function() {
                // Replace with your Excel export logic
                window.location.href = "{{ route('aktivs.export') }}?" + new URLSearchParams(window
                    .location.search).toString();
            });

            // Add animation when cards are in viewport
            const cards = document.querySelectorAll('.card-hover');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(card => {
                observer.observe(card);
            });
        });
    </script>
@endsection
