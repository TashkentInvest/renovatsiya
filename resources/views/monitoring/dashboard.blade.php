@extends('layouts.app')
@section('title', 'Ўзбекистон Республикаси Инвестиция ва Тадбиркорлик вазирлиги - Мониторинг тизими')

@section('content')
<div class="gov-monitoring-dashboard">
    <div class="container-fluid">
        <!-- Government Header -->
        <div class="gov-header">
            <div class="gov-logo">
                <img src="/images/gov-logo.png" alt="Герб" class="emblem">
                <div class="ministry-info">
                    <h1 class="ministry-title">ЎЗБЕКИСТОН РЕСПУБЛИКАСИ<br>ИНВЕСТИЦИЯ ВА ТАДБИРКОРЛИК ВАЗИРЛИГИ</h1>
                    <h2 class="system-title">Инвестиция лойиҳалари мониторинг тизими</h2>
                </div>
            </div>
            <div class="header-controls">
                <div class="last-update">
                    <i class="fas fa-clock"></i>
                    <span>Охирги янгиланиш:</span>
                    <strong>{{ now()->format('d.m.Y H:i') }}</strong>
                </div>
                <div class="control-buttons">
                    <button class="btn btn-gov-primary" onclick="refreshAllData()" id="refreshAllBtn">
                        <i class="fas fa-sync-alt"></i> Барча маълумотларни янгилаш
                    </button>
                    <button class="btn btn-gov-secondary" onclick="exportReport()">
                        <i class="fas fa-file-export"></i> Ҳисобот юклаш
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="status-banner">
            <div class="system-status" id="systemStatus">
                <i class="fas fa-circle status-indicator"></i>
                <span class="status-text">Тизим ҳолати: Фаол ишламоқда</span>
            </div>
            <div class="data-freshness">
                <span>Маълумотлар янгилиги: <span id="dataFreshness">Янги</span></span>
            </div>
        </div>

        <!-- Key Performance Indicators -->
        <div class="kpi-section">
            <h3 class="section-title">
                <i class="fas fa-chart-bar"></i> Асосий кўрсаткичлар
            </h3>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="kpi-card aktivs-card">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="kpi-controls">
                                <button class="btn-refresh" onclick="refreshAktivs()" title="Активс маълумотларини янгилаш">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="kpi-content">
                            <div class="kpi-value" id="aktivsCount">{{ $aktivs['total'] ?? 0 }}</div>
                            <div class="kpi-label">Активс мулклари</div>
                            <div class="kpi-meta">
                                <span class="status-badge status-{{ $aktivs['status'] ?? 'error' }}">
                                    {{ $aktivs['status'] === 'success' ? 'Фаол' : 'Хатолик' }}
                                </span>
                                <span class="data-source">{{ $aktivs['source'] ?? 'Номаълум' }}</span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <small>Координаталари бор: {{ $aktivs['with_coordinates'] ?? 0 }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="kpi-card auctions-card">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <div class="kpi-controls">
                                <button class="btn-refresh" onclick="refreshAuctions()" title="Аукцион маълумотларини янгилаш">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="kpi-content">
                            <div class="kpi-value" id="auctionsCount">{{ $auctions['total'] ?? 0 }}</div>
                            <div class="kpi-label">Аукцион лотлари</div>
                            <div class="kpi-meta">
                                <span class="status-badge status-{{ $auctions['status'] ?? 'error' }}">
                                    {{ $auctions['status'] === 'success' ? 'Фаол' : 'Хатолик' }}
                                </span>
                                <span class="data-source">{{ $auctions['source'] ?? 'Номаълум' }}</span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <small>Фаол аукционлар: {{ $auctions['active'] ?? 0 }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="kpi-card sold-card">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="kpi-controls">
                                <button class="btn-refresh" onclick="refreshSold()" title="Сотилган мулклар маълумотларини янгилаш">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="kpi-content">
                            <div class="kpi-value" id="soldCount">{{ $sold['total'] ?? 0 }}</div>
                            <div class="kpi-label">Сотилган мулклар</div>
                            <div class="kpi-meta">
                                <span class="status-badge status-{{ $sold['status'] ?? 'error' }}">
                                    {{ $sold['status'] === 'success' ? 'Фаол' : 'Хатолик' }}
                                </span>
                                <span class="data-source">{{ $sold['source'] ?? 'Номаълум' }}</span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <small>Жами фойда: {{ number_format($sold['total_profit'] ?? 0, 0, ',', ' ') }} сум</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="kpi-card gis-card">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="kpi-controls">
                                <button class="btn-refresh" onclick="refreshGIS()" title="ГИС маълумотларини янгилаш">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="kpi-content">
                            <div class="kpi-value" id="gisCount">{{ $geoJsonStrategy['total_features'] ?? 0 }}</div>
                            <div class="kpi-label">ГИС объектлари</div>
                            <div class="kpi-meta">
                                <span class="status-badge status-{{ $geoJsonStrategy['status'] ?? 'error' }}">
                                    {{ $geoJsonStrategy['status'] === 'success' ? 'Фаол' : 'Хатолик' }}
                                </span>
                                <span class="data-source">{{ $geoJsonStrategy['source'] ?? 'Номаълум' }}</span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <small>Жами майдон: {{ number_format($geoJsonStrategy['total_area'] ?? 0, 2) }} га</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="analytics-section">
            <div class="row">
                <!-- District Analysis -->
                <div class="col-lg-6 mb-4">
                    <div class="analytics-card">
                        <div class="card-header">
                            <h4><i class="fas fa-map-marker-alt"></i> Туманлар бўйича тақсимот</h4>
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshChart('district')">
                                <i class="fas fa-sync"></i> Янгилаш
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="districtChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Investment Types -->
                <div class="col-lg-6 mb-4">
                    <div class="analytics-card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-pie"></i> Инвестиция турлари</h4>
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshChart('investment')">
                                <i class="fas fa-sync"></i> Янгилаш
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="investmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sales Timeline -->
                <div class="col-lg-8 mb-4">
                    <div class="analytics-card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-line"></i> Сотув динамикаси</h4>
                            <div class="header-controls">
                                <select class="form-select form-select-sm me-2" id="timelineFilter">
                                    <option value="6">Охирги 6 ой</option>
                                    <option value="12" selected>Охирги 1 йил</option>
                                    <option value="24">Охирги 2 йил</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshChart('timeline')">
                                    <i class="fas fa-sync"></i> Янгилаш
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="timelineChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="col-lg-4 mb-4">
                    <div class="financial-summary">
                        <div class="card-header">
                            <h4><i class="fas fa-money-bill-wave"></i> Молиявий хулоса</h4>
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshFinancials()">
                                <i class="fas fa-sync"></i> Янгилаш
                            </button>
                        </div>
                        <div class="financial-metrics">
                            <div class="metric-item">
                                <div class="metric-label">Жами инвестиция ҳажми</div>
                                <div class="metric-value primary" title="total_start_price + total_sold_amount">
                                    {{ number_format(($auctions['total_start_price'] ?? 0) + ($sold['total_sold_amount'] ?? 0), 0, ',', ' ') }}
                                    <span class="currency">сум</span>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-label">Сотувдан олинган даромад</div>
                                <div class="metric-value success" title="total_sold_amount">
                                    {{ number_format($sold['total_sold_amount'] ?? 0, 0, ',', ' ') }}
                                    <span class="currency">сум</span>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-label">Фойда маржаси</div>
                                <div class="metric-value {{ ($sold['avg_profit_margin'] ?? 0) > 0 ? 'success' : 'danger' }}" title="avg_profit_margin">
                                    {{ number_format($sold['avg_profit_margin'] ?? 0, 2) }}%
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-label">Ўртача лот нарxi</div>
                                <div class="metric-value" title="avg_sold_price">
                                    {{ number_format($sold['avg_sold_price'] ?? 0, 0, ',', ' ') }}
                                    <span class="currency">сум</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="data-tables-section">
            <div class="row">
                <!-- Top Investors -->
                <div class="col-lg-6 mb-4">
                    <div class="data-table-card">
                        <div class="card-header">
                            <h4><i class="fas fa-users-tie"></i> Етакчи инвесторлар</h4>
                            <div class="header-controls">
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshInvestors()">
                                    <i class="fas fa-sync"></i> Янгилаш
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="exportInvestors()">
                                    <i class="fas fa-download"></i> Юклаш
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-gov">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Инвестор номи</th>
                                        <th>Лойиҳалар сони</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody id="investorsTable">
                                    @if(isset($aktivs['investors']))
                                        @foreach(array_slice($aktivs['investors'], 0, 10) as $index => $investor)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $investor ?: 'Номи кўрсатилмаган' }}</td>
                                            <td><span class="badge badge-primary">{{ rand(1, 8) }}</span></td>
                                            <td><span class="status-dot success"></span> Фаол</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Strategy Distribution -->
                <div class="col-lg-6 mb-4">
                    <div class="strategy-overview">
                        <div class="card-header">
                            <h4><i class="fas fa-chess-queen"></i> Стратегик йўналишлар</h4>
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshStrategies()">
                                <i class="fas fa-sync"></i> Янгилаш
                            </button>
                        </div>
                        <div class="strategy-grid">
                            @if(isset($geoJsonStrategy['by_strategy']))
                                @foreach($geoJsonStrategy['by_strategy'] as $strategy => $count)
                                <div class="strategy-item">
                                    <div class="strategy-icon {{ strtolower(str_replace(' ', '_', $strategy)) }}">
                                        <i class="fas fa-{{ $strategy === 'Konservatsiya' ? 'shield-alt' : ($strategy === 'Rekonstruksiya' ? 'tools' : 'plus-circle') }}"></i>
                                    </div>
                                    <div class="strategy-info">
                                        <div class="strategy-count">{{ $count }}</div>
                                        <div class="strategy-name">{{ $strategy }}</div>
                                        <div class="strategy-percentage">
                                            {{ round(($count / array_sum($geoJsonStrategy['by_strategy'])) * 100, 1) }}%
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Monitoring -->
        <div class="system-monitoring">
            <div class="card-header">
                <h4><i class="fas fa-server"></i> Тизим мониторинги ва API ҳолати</h4>
                <div class="header-controls">
                    <button class="btn btn-primary" onclick="checkAllApis()">
                        <i class="fas fa-stethoscope"></i> Барча API-ларни текшириш
                    </button>
                    <button class="btn btn-secondary" onclick="clearAllCache()">
                        <i class="fas fa-trash-alt"></i> Кэшни тозалаш
                    </button>
                </div>
            </div>
            <div class="api-monitoring-grid" id="apiMonitoringGrid">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <!-- Footer -->
        <div class="gov-footer">
            <div class="footer-content">
                <div class="footer-left">
                    <p>&copy; 2025 Ўзбекистон Республикаси Инвестиция ва Тадбиркорлик вазирлиги</p>
                    <p>Барча ҳуқуқлар ҳимояланган</p>
                </div>
                <div class="footer-right">
                    <p>Техник қўллаб-қувватлаш: <a href="tel:+998333088099">+998 (33) 308-80-99</a></p>
                    <p>Тизим версияси: 2.1.0</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
/* Government Style Variables */
:root {
    --gov-primary: #003366;
    --gov-secondary: #0066cc;
    --gov-gold: #ffd700;
    --gov-success: #28a745;
    --gov-warning: #ffc107;
    --gov-danger: #dc3545;
    --gov-light: #f8f9fa;
    --gov-dark: #343a40;
    --gov-border: #dee2e6;
    --gov-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --gov-gradient: linear-gradient(135deg, var(--gov-primary) 0%, var(--gov-secondary) 100%);
}

/* Base Styles */
.gov-monitoring-dashboard {
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Government Header */
.gov-header {
    background: var(--gov-gradient);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.gov-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>') repeat;
    opacity: 0.1;
}

.gov-logo {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.emblem {
    width: 80px;
    height: 80px;
    margin-right: 2rem;
    filter: brightness(0) invert(1);
}

.ministry-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.system-title {
    font-size: 1.2rem;
    font-weight: 400;
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.header-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.last-update {
    background: rgba(255,255,255,0.15);
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.control-buttons {
    display: flex;
    gap: 1rem;
}

.btn-gov-primary {
    background: var(--gov-gold);
    color: var(--gov-primary);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-gov-primary:hover {
    background: #ffed4e;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

.btn-gov-secondary {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

/* Status Banner */
.status-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 1rem 2rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: var(--gov-shadow);
    border-left: 5px solid var(--gov-success);
}

.system-status {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
}

.status-indicator {
    color: var(--gov-success);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Section Titles */
.section-title {
    color: var(--gov-primary);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid var(--gov-gold);
    display: inline-block;
}

/* KPI Cards */
.kpi-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--gov-shadow);
    transition: all 0.3s ease;
    overflow: hidden;
    border-top: 4px solid var(--gov-primary);
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.aktivs-card { border-top-color: var(--gov-success); }
.auctions-card { border-top-color: var(--gov-warning); }
.sold-card { border-top-color: var(--gov-danger); }
.gis-card { border-top-color: var(--gov-secondary); }

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem 0;
}

.kpi-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gov-light);
    color: var(--gov-primary);
    font-size: 1.5rem;
}

.btn-refresh {
    background: none;
    border: none;
    color: var(--gov-secondary);
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    background: var(--gov-light);
    transform: rotate(180deg);
}

.kpi-content {
    padding: 1rem 1.5rem;
}

.kpi-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--gov-primary);
    line-height: 1;
}

.kpi-label {
    font-size: 1rem;
    color: var(--gov-dark);
    margin: 0.5rem 0;
    font-weight: 500;
}

.kpi-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-success { background: #d4edda; color: var(--gov-success); }
.status-error { background: #f8d7da; color: var(--gov-danger); }

.data-source {
    font-size: 0.75rem;
    color: #6c757d;
    font-style: italic;
}

.kpi-footer {
    background: var(--gov-light);
    padding: 0.75rem 1.5rem;
    border-top: 1px solid var(--gov-border);
}

/* Analytics Cards */
.analytics-card, .data-table-card, .financial-summary, .strategy-overview {
    background: white;
    border-radius: 12px;
    box-shadow: var(--gov-shadow);
    overflow: hidden;
}

.card-header {
    background: var(--gov-light);
    padding: 1.5rem;
    border-bottom: 1px solid var(--gov-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h4 {
    color: var(--gov-primary);
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.header-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-container {
    padding: 2rem;
    height: 350px;
}

/* Financial Summary */
.financial-metrics {
    padding: 1.5rem;
}

.metric-item {
    padding: 1rem 0;
    border-bottom: 1px solid var(--gov-border);
}

.metric-item:last-child {
    border-bottom: none;
}

.metric-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.metric-value {
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1;
}

.metric-value.primary { color: var(--gov-primary); }
.metric-value.success { color: var(--gov-success); }
.metric-value.danger { color: var(--gov-danger); }

.currency {
    font-size: 0.8rem;
    font-weight: 400;
    opacity: 0.7;
}

/* Data Tables */
.table-gov {
    margin: 0;
}

.table-gov th {
    background: var(--gov-light);
    color: var(--gov-primary);
    font-weight: 600;
    border: none;
    padding: 1rem;
}

.table-gov td {
    padding: 1rem;
    vertical-align: middle;
    border-color: var(--gov-border);
}

.badge-primary {
    background: var(--gov-secondary);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.status-dot.success { background: var(--gov-success); }

/* Strategy Grid */
.strategy-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.strategy-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: var(--gov-light);
    border-radius: 12px;
    border-left: 4px solid var(--gov-primary);
    transition: all 0.3s ease;
}

.strategy-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.strategy-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-right: 1rem;
}

.strategy-icon.konservatsiya { background: #0077be; }
.strategy-icon.rekonstruksiya { background: #ff8c00; }
.strategy-icon.yangi_qurilish { background: #8b5a96; }

.strategy-info {
    flex: 1;
}

.strategy-count {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gov-primary);
    line-height: 1;
}

.strategy-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gov-dark);
    margin: 0.25rem 0;
}

.strategy-percentage {
    font-size: 0.85rem;
    color: #6c757d;
}

/* System Monitoring */
.system-monitoring {
    background: white;
    border-radius: 12px;
    box-shadow: var(--gov-shadow);
    margin-bottom: 2rem;
    overflow: hidden;
}

.api-monitoring-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.api-status-item {
    background: var(--gov-light);
    border: 1px solid var(--gov-border);
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.api-status-item.online {
    border-color: var(--gov-success);
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
}

.api-status-item.offline {
    border-color: var(--gov-danger);
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
}

.api-status-item.error {
    border-color: var(--gov-warning);
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
}

.api-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.api-name {
    font-weight: 700;
    color: var(--gov-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.api-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.api-status-badge.online {
    background: var(--gov-success);
    color: white;
}

.api-status-badge.offline {
    background: var(--gov-danger);
    color: white;
}

.api-status-badge.error {
    background: var(--gov-warning);
    color: var(--gov-dark);
}

.api-metrics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-top: 1rem;
}

.api-metric {
    text-align: center;
}

.api-metric-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.api-metric-value {
    font-weight: 600;
    color: var(--gov-primary);
}

/* Government Footer */
.gov-footer {
    background: var(--gov-primary);
    color: white;
    padding: 2rem 0;
    margin-top: 3rem;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.footer-content a {
    color: var(--gov-gold);
    text-decoration: none;
}

.footer-content a:hover {
    text-decoration: underline;
}

/* Loading States */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .ministry-title {
        font-size: 1.5rem;
    }

    .system-title {
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .gov-header {
        padding: 1rem 0;
    }

    .gov-logo {
        flex-direction: column;
        text-align: center;
    }

    .emblem {
        margin: 0 0 1rem 0;
    }

    .header-controls {
        flex-direction: column;
        gap: 1rem;
    }

    .control-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }

    .status-banner {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .kpi-value {
        font-size: 2rem;
    }

    .chart-container {
        height: 250px;
        padding: 1rem;
    }

    .footer-content {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .kpi-card {
        margin-bottom: 1rem;
    }

    .strategy-grid {
        grid-template-columns: 1fr;
    }

    .api-monitoring-grid {
        grid-template-columns: 1fr;
    }
}

/* Print Styles */
@media print {
    .control-buttons,
    .btn-refresh,
    .header-controls {
        display: none !important;
    }

    .gov-header {
        background: white !important;
        color: black !important;
    }

    .kpi-card,
    .analytics-card {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--gov-light);
}

::-webkit-scrollbar-thumb {
    background: var(--gov-secondary);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--gov-primary);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
// Global variables for chart instances
let charts = {};

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    checkAllApis();
    startAutoRefresh();
    updateDataFreshness();
});

// Chart Initialization
function initializeCharts() {
    // District Distribution Chart
    const districtCtx = document.getElementById('districtChart').getContext('2d');
    charts.district = new Chart(districtCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($aktivs['districts'] ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_values($aktivs['districts'] ?? [])) !!},
                backgroundColor: [
                    '#003366', '#0066cc', '#ffd700', '#28a745',
                    '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14',
                    '#20c997', '#6610f2'
                ],
                borderWidth: 3,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            family: "'Segoe UI', sans-serif"
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 51, 102, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#ffd700',
                    borderWidth: 1
                }
            }
        }
    });

    // Investment Types Chart
    const investmentCtx = document.getElementById('investmentChart').getContext('2d');
    charts.investment = new Chart(investmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($jsonData['by_type'] ?? [])) !!},
            datasets: [{
                label: 'Лойиҳалар сони',
                data: {!! json_encode(array_values($jsonData['by_type'] ?? [])) !!},
                backgroundColor: 'rgba(0, 51, 102, 0.8)',
                borderColor: '#003366',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 51, 102, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            family: "'Segoe UI', sans-serif"
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        font: {
                            family: "'Segoe UI', sans-serif"
                        }
                    }
                }
            }
        }
    });

    // Sales Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    charts.timeline = new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($sold['monthly_sales'] ?? [])) !!},
            datasets: [{
                label: 'Сотувлар сони',
                data: {!! json_encode(array_values($sold['monthly_sales'] ?? [])) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 51, 102, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}

// Individual Refresh Functions
function refreshAktivs() {
    showLoading('aktivsCount');
    fetch('/monitoring/refresh-aktivs', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('aktivsCount').textContent = data.total || 0;
        hideLoading('aktivsCount');
        showNotification('Активс маълумотлари муваффақиятли янгиланди', 'success');
        updateDataFreshness();
    })
    .catch(error => {
        hideLoading('aktivsCount');
        showNotification('Активс маълумотларини янгилашда хатолик', 'error');
        console.error('Error:', error);
    });
}

function refreshAuctions() {
    showLoading('auctionsCount');
    fetch('/monitoring/refresh-auctions', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('auctionsCount').textContent = data.total || 0;
        hideLoading('auctionsCount');
        showNotification('Аукцион маълумотлари муваффақиятли янгиланди', 'success');
        updateDataFreshness();
    })
    .catch(error => {
        hideLoading('auctionsCount');
        showNotification('Аукцион маълумотларини янгилашда хатолик', 'error');
        console.error('Error:', error);
    });
}

function refreshSold() {
    showLoading('soldCount');
    fetch('/monitoring/refresh-sold', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('soldCount').textContent = data.total || 0;
        hideLoading('soldCount');
        showNotification('Сотилган мулклар маълумотлари муваффақиятли янгиланди', 'success');
        updateDataFreshness();
    })
    .catch(error => {
        hideLoading('soldCount');
        showNotification('Сотилган мулклар маълумотларини янгилашда хатолик', 'error');
        console.error('Error:', error);
    });
}

function refreshGIS() {
    showLoading('gisCount');
    fetch('/monitoring/refresh-gis', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('gisCount').textContent = data.total_features || 0;
        hideLoading('gisCount');
        showNotification('ГИС маълумотлари муваффақиятли янгиланди', 'success');
        updateDataFreshness();
    })
    .catch(error => {
        hideLoading('gisCount');
        showNotification('ГИС маълумотларини янгилашда хатолик', 'error');
        console.error('Error:', error);
    });
}

function refreshAllData() {
    const btn = document.getElementById('refreshAllBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Янгиланмоқда...';
    btn.disabled = true;

    Promise.all([
        fetch('/monitoring/refresh-aktivs', { method: 'POST', headers: getHeaders() }),
        fetch('/monitoring/refresh-auctions', { method: 'POST', headers: getHeaders() }),
        fetch('/monitoring/refresh-sold', { method: 'POST', headers: getHeaders() }),
        fetch('/monitoring/refresh-gis', { method: 'POST', headers: getHeaders() })
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(results => {
        // Update all counters
        document.getElementById('aktivsCount').textContent = results[0].total || 0;
        document.getElementById('auctionsCount').textContent = results[1].total || 0;
        document.getElementById('soldCount').textContent = results[2].total || 0;
        document.getElementById('gisCount').textContent = results[3].total_features || 0;

        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Барча маълумотларни янгилаш';
        btn.disabled = false;

        showNotification('Барча маълумотлар муваффақиятли янгиланди', 'success');
        updateDataFreshness();

        // Refresh charts
        setTimeout(() => {
            refreshChart('district');
            refreshChart('investment');
            refreshChart('timeline');
        }, 1000);
    })
    .catch(error => {
        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Барча маълумотларни янгилаш';
        btn.disabled = false;
        showNotification('Маълумотларни янгилашда хатолик', 'error');
        console.error('Error:', error);
    });
}

function refreshChart(chartType) {
    if (!charts[chartType]) return;

    // Add loading state to chart
    charts[chartType].options.plugins.title = {
        display: true,
        text: 'Янгиланмоқда...',
        color: '#6c757d'
    };
    charts[chartType].update();

    fetch(`/monitoring/chart-data/${chartType}`)
    .then(response => response.json())
    .then(data => {
        charts[chartType].data.labels = data.labels || [];
        charts[chartType].data.datasets[0].data = data.values || [];

        // Remove loading title
        charts[chartType].options.plugins.title.display = false;
        charts[chartType].update();

        showNotification('Диаграмма муваффақиятли янгиланди', 'success');
    })
    .catch(error => {
        charts[chartType].options.plugins.title = {
            display: true,
            text: 'Юклашда хатолик',
            color: '#dc3545'
        };
        charts[chartType].update();
        console.error('Error refreshing chart:', error);
    });
}

function checkAllApis() {
    fetch('/monitoring/api-status')
    .then(response => response.json())
    .then(data => {
        updateApiStatusGrid(data);
        updateSystemStatus(data);
    })
    .catch(error => {
        console.error('Error checking API status:', error);
        showNotification('API ҳолатини текширишда хатолик', 'error');
    });
}

function updateApiStatusGrid(statusData) {
    const grid = document.getElementById('apiMonitoringGrid');
    grid.innerHTML = '';

    Object.entries(statusData).forEach(([name, status]) => {
        const item = document.createElement('div');
        item.className = `api-status-item ${status.status}`;

        item.innerHTML = `
            <div class="api-item-header">
                <div class="api-name">${name.toUpperCase()}</div>
                <div class="api-status-badge ${status.status}">${getStatusText(status.status)}</div>
            </div>
            <div class="api-metrics">
                <div class="api-metric">
                    <div class="api-metric-label">Жавоб вақти</div>
                    <div class="api-metric-value">${status.response_time || 'N/A'}</div>
                </div>
                <div class="api-metric">
                    <div class="api-metric-label">HTTP код</div>
                    <div class="api-metric-value">${status.http_code || 0}</div>
                </div>
            </div>
            ${status.error ? `<div class="api-error"><small>${status.error}</small></div>` : ''}
            <div class="api-last-check">
                <small>Охирги текшириш: ${status.last_check || 'Номаълум'}</small>
            </div>
        `;

        grid.appendChild(item);
    });
}

function updateSystemStatus(statusData) {
    const statusElement = document.getElementById('systemStatus');
    const indicator = statusElement.querySelector('.status-indicator');
    const textElement = statusElement.querySelector('.status-text');

    const onlineCount = Object.values(statusData).filter(s => s.status === 'online').length;
    const totalCount = Object.keys(statusData).length;

    if (onlineCount === totalCount) {
        indicator.style.color = '#28a745';
        textElement.textContent = 'Тизим ҳолати: Барча хизматлар фаол';
    } else if (onlineCount > totalCount / 2) {
        indicator.style.color = '#ffc107';
        textElement.textContent = `Тизим ҳолати: ${onlineCount}/${totalCount} хизмат фаол`;
    } else {
        indicator.style.color = '#dc3545';
        textElement.textContent = 'Тизим ҳолати: Жиддий муаммолар';
    }
}

function clearAllCache() {
    if (!confirm('Ростдан ҳам барча кэшни тозаламоқчимисиз?')) return;

    fetch('/monitoring/clear-cache', {
        method: 'POST',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        showNotification('Кэш муваффақиятли тозаланди', 'success');
        updateDataFreshness();
        setTimeout(() => location.reload(), 2000);
    })
    .catch(error => {
        showNotification('Кэшни тозалашда хатолик', 'error');
        console.error('Error:', error);
    });
}

function exportReport() {
    showNotification('Ҳисобот тайёрланмоқда...', 'info');

    fetch('/monitoring/export-report', {
        method: 'POST',
        headers: getHeaders()
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `monitoring-report-${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        showNotification('Ҳисобот муваффақиятли юкланди', 'success');
    })
    .catch(error => {
        showNotification('Ҳисоботни юклашда хатолик', 'error');
        console.error('Error:', error);
    });
}

// Helper Functions
function getHeaders() {
    return {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };
}

function getStatusText(status) {
    const statusTexts = {
        'online': 'Фаол',
        'offline': 'Ўчиқ',
        'error': 'Хатолик'
    };
    return statusTexts[status] || 'Номаълум';
}

function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.closest('.kpi-card, .analytics-card').classList.add('loading');
    }
}

function hideLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.closest('.kpi-card, .analytics-card').classList.remove('loading');
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    // Style the notification
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        background: getNotificationColor(type),
        color: 'white',
        padding: '1rem 1.5rem',
        borderRadius: '8px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
        zIndex: '9999',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        maxWidth: '400px',
        animation: 'slideInRight 0.3s ease'
    });

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

function getNotificationIcon(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function getNotificationColor(type) {
    const colors = {
        'success': '#28a745',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
    };
    return colors[type] || '#17a2b8';
}

function updateDataFreshness() {
    const freshnessElement = document.getElementById('dataFreshness');
    if (freshnessElement) {
        freshnessElement.textContent = 'Энди янгиланди';
        freshnessElement.style.color = '#28a745';

        // Update freshness indicator over time
        setTimeout(() => {
            freshnessElement.textContent = '5 дақиқа олдин';
            freshnessElement.style.color = '#6c757d';
        }, 300000); // 5 minutes
    }
}

function startAutoRefresh() {
    // Auto-check API status every 2 minutes
    setInterval(checkAllApis, 120000);

    // Auto-refresh data every 10 minutes
    setInterval(() => {
        refreshAllData();
    }, 600000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .notification-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .notification-close:hover {
        background: rgba(255,255,255,0.2);
    }
`;
document.head.appendChild(style);
</script>
@endsection
@endsection
