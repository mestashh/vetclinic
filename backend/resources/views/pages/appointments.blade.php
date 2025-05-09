@extends('layouts.app')

@section('title', 'Приёмы')

@section('content')
    <style>
        .appointments-wrapper {
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
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
            vertical-align: middle;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .action-buttons {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }

        .btn-icon {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .edit-btn { background-color: #3b82f6; }
        .delete-btn { background-color: #ef4444; }
        .confirm-btn { background-color: #10b981; }
        .cancel-btn { background-color: #6b7280; }

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

        input[type="text"], input[type="number"], input[type="datetime-local"], select {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }
    </style>

    <div class="appointments-wrapper">
        <h1>Приёмы</h1>
        @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
            <div class="top-btn">
                <button id="addAppointmentBtn">Добавить приём</button>
            </div>
        @endif
        <div style="margin-bottom: 1rem; text-align: center;">
            <input type="text" id="searchInput" placeholder="Поиск по приёмам..."
                   style="width: 300px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px;" />
        </div>
        <table id="appointmentsTable">
            <thead>
            <tr>
                <th>Клиент</th>
                <th>Питомец</th>
                <th>Ветеринар</th>
                <th>Дата и время</th>
                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <th>Действия</th>
                @endif
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = '{{ Auth::user()->role }}';
        window.currentUserId = {{ Auth::id() }};
    </script>
    @vite(['src/app.js'])
@endsection
