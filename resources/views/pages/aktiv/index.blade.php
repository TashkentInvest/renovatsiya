@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Объектлар сони: {{ $aktivs->total() ?? '' }}</h1>
        <a href="{{ route('aktivs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Янги актив яратиш
        </a>
    </div>

    @if ($aktivs->count())
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th scope="col"><i class="fas fa-user"></i> Фойдаланувчи</th>
                        <th scope="col"><i class="fas fa-map-marker-alt"></i>Туман,
                            Мфй /
                            Коча</th>
                        <th scope="col" width="50"><i class="fas fa-building"></i> Объект номи</th>

                        <th scope="col"><i class="fas fa-map-marker-alt"></i> Умумий
                            майдон</th>

                        <th scope="col"><i class="fas fa-calendar-alt"></i> Сана</th>
                        <th scope="col" class="text-center"><i class="fas fa-cogs"></i> Ҳаракатлар</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aktivs as $aktiv)
                        <tr>
                            <td class="fw-bold">
                                {{ $aktiv->user->name ?? 'No Name' }}<br>
                                <small class="text-muted">{{ $aktiv->user->email ?? 'No Email' }}</small>
                            </td>
                            <td style="width: 100px" class="text-truncate"
                                title="{{ $aktiv->subStreet->district->name_uz ?? 'Маълумот йўқ' }}">
                                {{ $aktiv->subStreet->district->name_uz ?? 'Маълумот йўқ' }}
                                {{ $aktiv->street->name ?? 'Маълумот йўқ' }},
                            </td>
                            <td style="max-width: 200px" class="text-truncate" title="...">

                                {{ $aktiv->object_name }}

                                <style>
                                    .text-truncate {
                                        word-wrap: break-word;
                                        word-break: break-word;
                                        white-space: normal;
                                    }
                                </style>
                            </td>
                            {{-- <td>{{ $aktiv->balance_keeper }}</td> --}}

                            <td> {{ $aktiv->total_area ?? 'Маълумот йўқ' }} кв.м</td>
                            <td>{{ $aktiv->created_at->format('d-m-Y H:i') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('aktivs.show', $aktiv) }}" class="btn btn-info btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Кўриш">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if (auth()->user()->roles[0]->name != 'Manager')
                                        <a href="{{ route('aktivs.edit', $aktiv) }}" class="btn btn-warning btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Таҳрирлаш">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- <form action="{{ route('aktivs.destroy', $aktiv) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Ўчириш"
                                                onclick="return confirm('Сиз ростдан ҳам бу объектни ўчиришни истайсизми?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form> --}}
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="d-flex justify-content-center mt-4">
            {{ $aktivs->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="alert alert-warning text-center mt-4">
            <i class="fas fa-exclamation-circle"></i> Объектлар топилмади.
        </div>
    @endif
@endsection


@section('scripts')
    <!-- Include Font Awesome for Icons and Tooltip Initialization -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Initialize tooltips
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection
