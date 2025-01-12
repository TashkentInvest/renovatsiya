@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 bg-light sidebar py-4">
                <!-- Sidebar content if any -->
            </div>
          
            <!-- Main Content -->
            <div class="col-md-10 col-lg-12">
                <div class="container py-4">
                    <h1 class="mb-4 text-primary">Бошқарув панели</h1>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('aktivs.dashboard') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <select name="district_id" class="form-control">
                                        <option value="">Туманни танланг</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}"
                                                {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Филтр
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Key Metrics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                                        <div class="ml-3">
                                            <h5 class="card-title text-primary">Жами Активлар</h5>
                                            <p class="card-text h4">{{ $totalAktivs }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building fa-3x text-success"></i>
                                        <div class="ml-3">
                                            <h5 class="card-title text-success">Жами Турар Жой Майдони (м²)</h5>
                                            <p class="card-text h4">{{ number_format($totalTurarJoy, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-warning">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building fa-3x text-warning"></i>
                                        <div class="ml-3">
                                            <h5 class="card-title text-warning">Жами Нотурар Жой Майдони (м²)</h5>
                                            <p class="card-text h4">{{ number_format($totalNoturarJoy, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parking Data Chart -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Паркинг маълумотлари</h5>
                                    <canvas id="parkingPieChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Aktiv Records Line Chart -->
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Ойлар бўйича активлар</h5>
                                    <canvas id="monthlyLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aktiv List Table with Sorting & Pagination -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Активлар рўйхати</h5>
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Ҳаракат</th>
                                                <th>Жойлашув</th>
                                                <th>Умумий майдон</th>
                                                <th>Яратилган сана</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($aktivs as $aktiv)
                                                <tr>
                                                    <td>{{ $aktiv->id }}</td>
                                                    <td>{{ $aktiv->action }}</td>
                                                    <td>{{ $aktiv->location }}</td>
                                                    <td>{{ $aktiv->total_area }}</td>
                                                    <td>{{ $aktiv->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $aktivs->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Parking Pie Chart
        var ctxPie = document.getElementById('parkingPieChart').getContext('2d');
        var parkingPieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Вақтинчалик Паркинг', 'Доимий Паркинг'],
                datasets: [{
                    data: [{{ $parkingData['vaqtinchalik'] }}, {{ $parkingData['doimiy'] }}],
                    backgroundColor: ['#A8DADC', '#457B9D'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });

        // Monthly Aktiv Records Line Chart
        var ctxLine = document.getElementById('monthlyLineChart').getContext('2d');
        var monthlyLineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Январ', 'Феврал', 'Март', 'Апрел', 'Май', 'Июн', 'Июл', 'Август', 'Сентябр', 'Октябр', 'Ноябр', 'Декабр'],
                datasets: [{
                    label: 'Ойлар бўйича активлар',
                    data: [
                        {{ isset($monthlyData[1]) ? $monthlyData[1] : 0 }},
                        {{ isset($monthlyData[2]) ? $monthlyData[2] : 0 }},
                        {{ isset($monthlyData[3]) ? $monthlyData[3] : 0 }},
                        {{ isset($monthlyData[4]) ? $monthlyData[4] : 0 }},
                        {{ isset($monthlyData[5]) ? $monthlyData[5] : 0 }},
                        {{ isset($monthlyData[6]) ? $monthlyData[6] : 0 }},
                        {{ isset($monthlyData[7]) ? $monthlyData[7] : 0 }},
                        {{ isset($monthlyData[8]) ? $monthlyData[8] : 0 }},
                        {{ isset($monthlyData[9]) ? $monthlyData[9] : 0 }},
                        {{ isset($monthlyData[10]) ? $monthlyData[10] : 0 }},
                        {{ isset($monthlyData[11]) ? $monthlyData[11] : 0 }},
                        {{ isset($monthlyData[12]) ? $monthlyData[12] : 0 }}
                    ],
                    borderColor: '#457B9D',
                    backgroundColor: 'rgba(69, 123, 157, 0.1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection