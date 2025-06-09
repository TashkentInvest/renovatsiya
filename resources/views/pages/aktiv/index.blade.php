@extends('layouts.app')

@section('title', 'Активлар рўйхати')


@section('content')
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1e40af;
            --primary-light: #3b82f6;
            --primary-dark: #1e3a8a;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .main-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .main-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            margin-bottom: 2rem;
        }

        .filter-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: var(--white);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
            outline: none;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.875rem;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-danger {
            background: transparent;
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            color: white;
        }

        .btn-outline-success {
            background: transparent;
            color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-outline-success:hover {
            background: var(--success-color);
            color: white;
        }

        /* Data Table */
        .data-table-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        }

        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .table tbody td {
            padding: 1rem;
            border-top: 1px solid var(--gray-200);
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(30, 64, 175, 0.02);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .status-inactive {
            background: rgba(107, 114, 128, 0.1);
            color: var(--gray-600);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        /* Charts Container */
        .chart-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        /* Simple Chart Placeholder */
        .simple-chart {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border-radius: var(--border-radius);
            border: 2px dashed var(--gray-300);
            color: var(--gray-500);
        }

       /* Add this CSS to your existing styles */

/* Enhanced Modal Styles - Beautiful, Responsive, Understandable */
.modal-content {
    border: none;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-xl);
    max-height: 90vh;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    padding: 1.5rem 2rem;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.modal-header .modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-body {
    padding: 0;
    max-height: calc(90vh - 140px);
    overflow-y: auto;
    overflow-x: hidden;
}

.modal-body-content {
    padding: 2rem;
}

/* Custom scrollbar for modal */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

/* Enhanced Info Cards */
.info-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s ease;
}

.info-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}

.info-card h6 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Info Grid Layout */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-light);
    transition: all 0.2s ease;
}

.info-item:hover {
    background: var(--primary-light);
    color: white;
    transform: translateX(2px);
}

.info-item:hover .info-label {
    color: rgba(255, 255, 255, 0.9);
}

.info-item:hover .info-value {
    color: white;
}

.info-label {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-value {
    color: var(--gray-900);
    font-weight: 700;
    font-size: 1rem;
    word-break: break-word;
}

/* Tabs for Modal Content */
.modal-tabs {
    display: flex;
    border-bottom: 2px solid var(--gray-200);
    margin-bottom: 2rem;
    background: var(--gray-50);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    padding: 0 1rem;
    overflow-x: auto;
}

.modal-tab {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    cursor: pointer;
    font-weight: 500;
    color: var(--gray-600);
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

.modal-tab.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: white;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.modal-tab:hover {
    color: var(--primary-color);
    background: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Enhanced Status Badge */
.modal .status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.modal .status-badge::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

/* Enhanced Document List */
.document-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    background: var(--gray-50);
    padding: 1rem;
}

.document-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    margin-bottom: 0.75rem;
    background: white;
    text-decoration: none;
    color: var(--gray-700);
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.document-item:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.document-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius);
    margin-right: 1rem;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

.document-item:hover .document-icon {
    background: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

/* Area Breakdown Visualization */
.area-breakdown {
    background: var(--white);
    padding: 1rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.progress {
    height: 12px;
    border-radius: 6px;
    background: var(--gray-200);
}

.progress-bar {
    border-radius: 6px;
    transition: width 0.3s ease;
}

/* Additional Info Styling */
.additional-info {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-top: 1rem;
    line-height: 1.6;
}

.additional-info p {
    margin-bottom: 1rem;
}

.additional-info p:last-child {
    margin-bottom: 0;
}

/* Image Container */
.image-container {
    position: relative;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.image-container img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-container:hover img {
    transform: scale(1.02);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: none;
    }

    .modal-content {
        max-height: 95vh;
    }

    .modal-header,
    .modal-footer {
        padding: 1rem;
    }

    .modal-body-content {
        padding: 1rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .modal-tabs {
        padding: 0;
        margin: 0 -1rem 1rem -1rem;
    }

    .modal-tab {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    .modal-header .modal-title {
        font-size: 1.25rem;
    }
}
        .info-card {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .info-card h6 {
            color: var(--gray-700);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .info-value {
            color: var(--gray-900);
            font-weight: 600;
        }

        /* Document list styles */
        .document-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .document-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            margin-bottom: 0.5rem;
            background: white;
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.2s ease;
        }

        .document-item:hover {
            background: var(--gray-50);
            border-color: var(--primary-color);
            color: var(--primary-color);
            text-decoration: none;
        }

        .document-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin-right: 0.75rem;
            font-size: 0.875rem;
        }

        .doc-pdf {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .doc-kmz {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .filter-section {
                padding: 1rem;
            }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: var(--gray-600);
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }
    </style>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Юкланмоқда...</span>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="main-title">
                            <i class="fas fa-building me-3"></i>
                            Активлар рўйхати
                        </h1>
                        <p class="main-subtitle" id="totalCount">Юкланмоқда...</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column">
                            <p class="mb-1">
                                <i class="fas fa-calendar me-2"></i>
                                <span id="currentDate"></span>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-user me-2"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="stats-grid fade-in" id="statsGrid">
                <!-- Stats will be loaded dynamically -->
            </div>
        </section>

        <!-- Charts Section -->
        <section class="charts-section" style="display: none !important;">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="chart-container fade-in">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-pie me-2"></i>
                            Ҳолат бўйича тақсимот
                        </h3>
                        <div class="simple-chart" id="statusChart">
                            <div class="text-center">
                                <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                <p>График юкланмоқда...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="chart-container fade-in">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-bar me-2"></i>
                            Туманлар бўйича тақсимот
                        </h3>
                        <div class="simple-chart" id="districtChart">
                            <div class="text-center">
                                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                <p>График юкланмоқда...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filter Section -->
        <section class="filter-section slide-up">
            <h3 class="filter-title">
                <i class="fas fa-filter"></i>
                Филтрлаш ва қидириш
            </h3>
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Қидириш</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Қидириш..." id="searchInput">
                    </div>
                </div>


                <div class="col-md-3">
                    <label class="form-label">Туман</label>
                    <select name="district_id" class="form-select" id="districtSelect">
                        <option value="">Барча туманлар</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ҳолат</label>
                    <select name="status" class="form-select" id="statusSelect">
                        <option value="">Барча ҳолатлар</option>
                        <option value="active">Актив</option>
                        <option value="inactive">Ноактив</option>
                        <option value="pending">Кутилмоқда</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Инвестор</label>
                    <input type="text" name="investor" class="form-control" placeholder="Инвестор номи..." id="investorInput">
                </div>

                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Қидириш
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="clearBtn">
                            <i class="fas fa-times"></i>
                            Тозалаш
                        </button>
                        <button type="button" class="btn btn-outline-success" id="exportBtn">
                            <i class="fas fa-file-excel"></i>
                            Excel экспорт
                        </button>
                        <a href="{{ route('aktivs.myMap') }}" class="btn btn-primary ms-auto">
                            <i class="fas fa-map"></i>
                            Интерактив карта
                        </a>
                    </div>
                </div>
            </form>
        </section>

        <!-- Data Table -->
        <section class="data-table-container slide-up">
            <div class="table-responsive">
                <table class="table" id="aktivsTable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">№</th>
                            <th>Туман</th>
                            <th>Маҳалла</th>
                            <th>Ҳудуд майдони (га)</th>
                            <th>Қурилиш майдони (м²)</th>
                            <th>Инвестор</th>
                            <th>Ҳолат</th>
                            <th style="width: 120px;">Амаллар</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Кўрсатилмоқда <span id="showingFrom">0</span> - <span id="showingTo">0</span> из <span id="totalRecords">0</span>
                </div>
                <nav>
                    <ul class="pagination mb-0" id="pagination">
                        <!-- Pagination will be generated dynamically -->
                    </ul>
                </nav>
            </div>
        </section>
    </div>

    <!-- Modal for Aktiv Details -->
    <div class="modal fade" id="aktivModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Актив маълумотлари
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Ёпиш
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container"></div>
<script>
        // Global variables
        let allData = [];
        let filteredData = [];
        let currentPage = 1;
        const itemsPerPage = 10;
        const API_BASE_URL = '{{ url("/api/aktivs") }}';

        // CSRF Token for Laravel
        const csrfToken = '{{ csrf_token() }}';

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            fetchAktivsData();
            setupEventListeners();
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });

        // Fetch data from API
        async function fetchAktivsData() {
            try {
                showLoading(true);
                const response = await fetch(API_BASE_URL, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data && data.lots) {
                    allData = data.lots;
                    filteredData = [...allData];

                    updateStatistics();
                    loadTableData();
                    createSimpleCharts();
                    populateFilters();
                } else {
                    throw new Error('Invalid data format received');
                }
            } catch (error) {
                console.error('Маълумотларни юклашда хатолик:', error);
                showError('Маълумотларни юклашда хатолик юз берди: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        // Show/hide loading overlay
        function showLoading(show) {
            document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
        }

        // Show error message
        function showError(message) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <h3>Хатолик юз берди</h3>
                            <p>${message}</p>
                            <button class="btn btn-primary" onclick="fetchAktivsData()">
                                <i class="fas fa-refresh me-1"></i>
                                Қайта юклаш
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Update statistics
        function updateStatistics() {
            const totalAktivs = allData.length;
            const totalResidentialArea = allData.reduce((sum, item) => sum + parseFloat(item.residential_area || 0), 0);
            const totalNonResidentialArea = allData.reduce((sum, item) => sum + parseFloat(item.non_residential_area || 0), 0);
            const totalBuildingArea = allData.reduce((sum, item) => sum + parseFloat(item.total_building_area || 0), 0);
            const totalPopulation = allData.reduce((sum, item) => sum + parseInt(item.population || 0), 0);
            const totalAreaHectare = allData.reduce((sum, item) => sum + parseFloat(item.area_hectare || 0), 0);

            // Update header count
            document.getElementById('totalCount').textContent = `Жами: ${totalAktivs.toLocaleString()} та актив`;

            // Create stats cards
            const statsGrid = document.getElementById('statsGrid');
            statsGrid.innerHTML = `
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light));">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-value">${totalAktivs.toLocaleString()}</div>
                    <div class="stat-label">Жами активлар</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--info-color), #0891b2);">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-value">${totalResidentialArea.toLocaleString()} м²</div>
                    <div class="stat-label">Турар жой майдони</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning-color), #d97706);">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-value">${totalNonResidentialArea.toLocaleString()} м²</div>
                    <div class="stat-label">Нотурар жой майдони</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--danger-color), #dc2626);">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <div class="stat-value">${totalBuildingArea.toLocaleString()} м²</div>
                    <div class="stat-label">Қурилиш майдони</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--success-color), #059669);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">${totalPopulation.toLocaleString()}</div>
                    <div class="stat-label">Аҳоли сони</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="stat-value">${totalAreaHectare.toLocaleString()} га</div>
                    <div class="stat-label">Жами ҳудуд майдони</div>
                </div>
            `;
        }

        // Create simple charts without Chart.js dependency
        function createSimpleCharts() {
            // Status distribution
            const statusCounts = getStatusCounts();
            const statusChart = document.getElementById('statusChart');

            let statusHTML = '<div class="text-center"><h5 class="mb-3">Ҳолат бўйича тақсимот</h5>';
            Object.entries(statusCounts).forEach(([status, count]) => {
                const percentage = ((count / allData.length) * 100).toFixed(1);
                const color = status === 'Актив' ? 'var(--success-color)' :
                             status === 'Кутилмоқда' ? 'var(--warning-color)' : 'var(--gray-500)';

                statusHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded">
                        <div class="d-flex align-items-center">
                            <div style="width: 12px; height: 12px; background: ${color}; border-radius: 50%; margin-right: 8px;"></div>
                            <span>${status}</span>
                        </div>
                        <div>
                            <strong>${count}</strong> <small>(${percentage}%)</small>
                        </div>
                    </div>
                `;
            });
            statusHTML += '</div>';
            statusChart.innerHTML = statusHTML;

            // District distribution
            const districtCounts = getDistrictCounts();
            const districtChart = document.getElementById('districtChart');

            let districtHTML = '<div class="text-center"><h5 class="mb-3">Туманлар бўйича тақсимот</h5>';
            Object.entries(districtCounts).slice(0, 5).forEach(([district, count]) => {
                const percentage = ((count / allData.length) * 100).toFixed(1);

                districtHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded">
                        <span class="text-truncate" style="max-width: 150px;">${district}</span>
                        <div>
                            <strong>${count}</strong> <small>(${percentage}%)</small>
                        </div>
                    </div>
                `;
            });

            if (Object.keys(districtCounts).length > 5) {
                districtHTML += `<small class="text-muted">... ва яна ${Object.keys(districtCounts).length - 5} та туман</small>`;
            }
            districtHTML += '</div>';
            districtChart.innerHTML = districtHTML;
        }

        // Get status counts for chart
        function getStatusCounts() {
            const counts = {};
            allData.forEach(item => {
                const status = getStatusText(item.status);
                counts[status] = (counts[status] || 0) + 1;
            });
            return counts;
        }

        // Get district counts for chart
        function getDistrictCounts() {
            const counts = {};
            allData.forEach(item => {
                const district = item.district_name || 'Номаълум';
                counts[district] = (counts[district] || 0) + 1;
            });
            return counts;
        }

        // Populate filter dropdowns
        function populateFilters() {
            // Populate districts
            const districts = [...new Set(allData.map(item => item.district_name).filter(Boolean))];
            const districtSelect = document.getElementById('districtSelect');
            districtSelect.innerHTML = '<option value="">Барча туманлар</option>';
            districts.forEach(district => {
                districtSelect.innerHTML += `<option value="${district}">${district}</option>`;
            });
        }

        // Load table data
        function loadTableData(page = 1) {
            const tableBody = document.getElementById('tableBody');
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);

            if (pageData.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <h3>Маълумот топилмади</h3>
                                <p>Филтр параметрларини ўзгартириб кўринг</p>
                                <button class="btn btn-primary" onclick="clearFilters()">
                                    <i class="fas fa-refresh me-1"></i>
                                    Филтрни тозалаш
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                updatePagination(0, 0, 0);
                return;
            }

            tableBody.innerHTML = pageData.map((aktiv, index) => `
                <tr class="aktiv-row">
                    <td>${startIndex + index + 1}</td>
                    <td>${aktiv.district_name || 'Номаълум'}</td>
                    <td>${aktiv.neighborhood_name || 'Номаълум'}</td>
                    <td>${parseFloat(aktiv.area_hectare || 0).toFixed(2)}</td>
                    <td>${parseFloat(aktiv.total_building_area || 0).toLocaleString()}</td>
                    <td>${aktiv.investor || 'Номаълум'}</td>
                    <td>
                        <span class="status-badge ${getStatusClass(aktiv.status)}">
                            ${getStatusText(aktiv.status)}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" onclick="showAktivDetails(${aktiv.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `).join('');

            updatePagination(startIndex + 1, Math.min(endIndex, filteredData.length), filteredData.length);
        }

        // Get status class for styling
        function getStatusClass(status) {
            const statusNum = parseInt(status);
            if (statusNum >= 8) return 'status-active';
            if (statusNum >= 5) return 'status-pending';
            return 'status-inactive';
        }

        // Get status text
        function getStatusText(status) {
            const statusNum = parseInt(status);
            if (statusNum >= 8) return 'Актив';
            if (statusNum >= 5) return 'Кутилмоқда';
            return 'Ноактив';
        }

        // Update pagination
        function updatePagination(from, to, total) {
            document.getElementById('showingFrom').textContent = from;
            document.getElementById('showingTo').textContent = to;
            document.getElementById('totalRecords').textContent = total;

            const totalPages = Math.ceil(total / itemsPerPage);
            const pagination = document.getElementById('pagination');

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            if (currentPage > 1) {
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                `;
            }

            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                `;
            }

            pagination.innerHTML = paginationHTML;
        }

        // Change page
        function changePage(page) {
            currentPage = page;
            loadTableData(page);
        }

        // Show aktiv details in modal
        function showAktivDetails(aktivId) {
            const aktiv = allData.find(item => item.id === aktivId);
            if (!aktiv) return;

            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <div class="row g-4">
                    <!-- Main Information -->
                    <div class="col-12">
                        <div class="info-card">
                            <h6><i class="fas fa-info-circle me-2"></i>Умумий маълумотлар</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Туман:</div>
                                        <div class="info-value">${aktiv.district_name || 'Номаълум'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Маҳалла:</div>
                                        <div class="info-value">${aktiv.neighborhood_name || 'Номаълум'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Инвестор:</div>
                                        <div class="info-value">${aktiv.investor || 'Номаълум'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Қарор рақами:</div>
                                        <div class="info-value">${aktiv.decision_number || 'Номаълум'}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Ҳолат:</div>
                                        <div class="info-value">
                                            <span class="status-badge ${getStatusClass(aktiv.status)}">
                                                ${getStatusText(aktiv.status)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Аҳоли сони:</div>
                                        <div class="info-value">${aktiv.population || 'Номаълум'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Уй хўжаликлари:</div>
                                        <div class="info-value">${aktiv.household_count || 'Номаълум'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Кадастр гувоҳнома:</div>
                                        <div class="info-value">${aktiv.cadastre_certificate || 'Номаълум'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Information -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="fas fa-ruler-combined me-2"></i>Майдонлар</h6>
                            <div class="info-item">
                                <div class="info-label">Ҳудуд майдони (га):</div>
                                <div class="info-value">${parseFloat(aktiv.area_hectare || 0).toFixed(2)}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Жами қурилиш майдони (м²):</div>
                                <div class="info-value">${parseFloat(aktiv.total_building_area || 0).toLocaleString()}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Турар жой майдони (м²):</div>
                                <div class="info-value">${parseFloat(aktiv.residential_area || 0).toLocaleString()}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Нотурар жой майдони (м²):</div>
                                <div class="info-value">${parseFloat(aktiv.non_residential_area || 0).toLocaleString()}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Туташ ҳудуд (м²):</div>
                                <div class="info-value">${parseFloat(aktiv.adjacent_area || 0).toLocaleString()}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Construction Information -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="fas fa-building me-2"></i>Қурилиш маълумотлари</h6>
                            <div class="info-item">
                                <div class="info-label">УМН коэффициент:</div>
                                <div class="info-value">${aktiv.umn_coefficient || 'Номаълум'}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">ҚМН фоизи:</div>
                                <div class="info-value">${aktiv.qmn_percentage || 'Номаълум'}%</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Белгиланган қаватлар:</div>
                                <div class="info-value">${aktiv.designated_floors || 'Номаълум'}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Таклиф этилган қаватлар:</div>
                                <div class="info-value">${aktiv.proposed_floors || 'Номаълум'}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Ҳудуд стратегияси:</div>
                                <div class="info-value">${aktiv.area_strategy || 'Номаълум'}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    ${aktiv.documents && aktiv.documents.length > 0 ? `
                    <div class="col-12">
                        <div class="info-card">
                            <h6><i class="fas fa-file-alt me-2"></i>Ҳужжатлар</h6>
                            <div class="document-list">
                                ${aktiv.documents.map(doc => `
                                    <a href="${doc.url}" target="_blank" class="document-item">
                                        <div class="document-icon ${doc.doc_type.includes('pdf') ? 'doc-pdf' : 'doc-kmz'}">
                                            <i class="fas fa-${doc.doc_type.includes('pdf') ? 'file-pdf' : 'file-archive'}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">${doc.filename}</div>
                                            <small class="text-muted">${doc.doc_type.replace('-', ' ').toUpperCase()}</small>
                                        </div>
                                        <i class="fas fa-external-link-alt ms-2"></i>
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- Additional Information -->
                    ${aktiv.object_information || aktiv.additional_information ? `
                    <div class="col-12">
                        <div class="info-card">
                            <h6><i class="fas fa-comment-alt me-2"></i>Қўшимча маълумотлар</h6>
                            ${aktiv.object_information ? `
                                <div class="info-item">
                                    <div class="info-label">Ҳудуддаги объектлар:</div>
                                    <div class="info-value">${aktiv.object_information}</div>
                                </div>
                            ` : ''}
                            ${aktiv.additional_information ? `
                                <div class="info-item">
                                    <div class="info-label">Қўшимча маълумот:</div>
                                    <div class="info-value">${aktiv.additional_information}</div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    ` : ''}

                    <!-- Main Image -->
                    ${aktiv.main_image && aktiv.main_image !== 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png' ? `
                    <div class="col-12">
                        <div class="info-card">
                            <h6><i class="fas fa-image me-2"></i>Асосий расм</h6>
                            <img src="${aktiv.main_image}" alt="Актив расми" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;"
                                 onerror="this.style.display='none'">
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;

            // Use Bootstrap's modal API if available, otherwise fallback
            const modalElement = document.getElementById('aktivModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                // Fallback for when Bootstrap modal is not available
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.setAttribute('role', 'dialog');

                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modal-backdrop';
                document.body.appendChild(backdrop);

                // Close modal function
                window.closeModal = function() {
                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');
                    document.getElementById('modal-backdrop').remove();
                };

                // Add close event to backdrop and close button
                backdrop.onclick = window.closeModal;
                modalElement.querySelector('.btn-close').onclick = window.closeModal;
            }
        }

        // Filter data
        function filterData() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const district = document.getElementById('districtSelect').value;
            const status = document.getElementById('statusSelect').value;
            const investor = document.getElementById('investorInput').value.toLowerCase();

            filteredData = allData.filter(item => {
                const searchMatch = !search ||
                    (item.district_name && item.district_name.toLowerCase().includes(search)) ||
                    (item.neighborhood_name && item.neighborhood_name.toLowerCase().includes(search)) ||
                    (item.investor && item.investor.toLowerCase().includes(search));

                const districtMatch = !district || item.district_name === district;

                const statusMatch = !status || getStatusText(item.status).toLowerCase() === status;

                const investorMatch = !investor ||
                    (item.investor && item.investor.toLowerCase().includes(investor));

                return searchMatch && districtMatch && statusMatch && investorMatch;
            });

            currentPage = 1;
            loadTableData();
            createSimpleCharts(); // Update charts with filtered data
        }

        // Clear filters
        function clearFilters() {
            document.getElementById('filterForm').reset();
            filteredData = [...allData];
            currentPage = 1;
            loadTableData();
            createSimpleCharts(); // Update charts with all data
        }

        // Export to Excel
        function exportToExcel() {
            // Create form data for export
            const params = new URLSearchParams({
                search: document.getElementById('searchInput').value,
                district_name: document.getElementById('districtSelect').value,
                status: document.getElementById('statusSelect').value,
                investor: document.getElementById('investorInput').value,
                _token: csrfToken
            });

            // Remove empty parameters
            for (let [key, value] of [...params.entries()]) {
                if (!value) {
                    params.delete(key);
                }
            }

            // Navigate to export URL
            window.location.href = '{{ route("aktivs.export") }}?' + params.toString();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Filter form
            document.getElementById('filterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                filterData();
            });

            // Clear button
            document.getElementById('clearBtn').addEventListener('click', clearFilters);

            // Export button
            document.getElementById('exportBtn').addEventListener('click', exportToExcel);

            // Real-time search
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(filterData, 500);
            });

            // Filter dropdowns
            document.getElementById('districtSelect').addEventListener('change', filterData);
            document.getElementById('statusSelect').addEventListener('change', filterData);
            document.getElementById('investorInput').addEventListener('input', function() {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(filterData, 500);
            });
        }

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const formatted = now.toLocaleString('uz-UZ', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentDate').textContent = formatted;
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;

            const toastId = 'toast_' + Date.now();
            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const toastHTML = `
                <div class="alert alert-dismissible ${bgClass} text-white" id="${toastId}" role="alert">
                    <i class="fas ${iconClass} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('${toastId}').remove()"></button>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const toastElement = document.getElementById(toastId);
                if (toastElement) {
                    toastElement.remove();
                }
            }, 5000);
        }

        // Show Laravel flash messages
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('error') }}', 'error');
            });
        @endif

        // Prevent default link behavior and add safety checks
        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('.page-link');
            if (pageLink) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection


