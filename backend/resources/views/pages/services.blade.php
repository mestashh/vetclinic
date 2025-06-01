@extends('layouts.app')

@section('title', 'Услуги и варианты услуг')

@section('content')
    <style>
        .services-wrapper {
            max-width: 1000px;
            margin: 2rem auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            font-family: sans-serif;
        }

        h1 {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .btn-icon {
            padding: 0.3rem 0.6rem;
            font-size: 0.875rem;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .confirm-btn { background-color: #10b981; }
        .cancel-btn { background-color: #6b7280; }
        .confirm-btn:hover { background-color: #059669; }
        .cancel-btn:hover { background-color: #4b5563; }

        .action-buttons {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }

        .top-btn {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .top-btn button {
            background-color: #2563eb;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }
    </style>

    <div class="services-wrapper">
        <h1>Услуги</h1>

        @if(Auth::user()->role === 'superadmin')
            <div class="top-btn">
                <button id="addServiceBtn">Добавить услугу</button>
            </div>
        @endif

        <div style="margin-bottom: 1rem; text-align: center;">
            <input type="text" id="searchInput" placeholder="Поиск по услугам..."
                   style="width: 300px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px;" />
        </div>

        <div class="overflow-x-auto">
            <table id="servicesTable">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Описание</th>
                    @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                        <th>Действия</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($services as $service)
                    <tr data-id="{{ $service->id }}" class="service-row">
                        <td>
                            <button class="toggle-items" style="margin-right: 5px;">▶️</button>
                            <span class="service-name">{{ $service->name }}</span>
                        </td>
                        <td class="service-desc">{{ $service->description }}</td>
                        @if(Auth::user()->role === 'superadmin')
                            <td class="action-buttons">
                                <button class="add-variant-btn btn-icon confirm-btn">➕ Вариант</button>
                                <button class="edit-btn btn-icon edit-btn">✏️</button>
                                <button class="delete-btn btn-icon cancel-btn">🗑️</button>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = '{{ Auth::user()->role }}';
        window.initialServices = @json($services);
    </script>
    @vite(['src/app.js'])
@endsection
