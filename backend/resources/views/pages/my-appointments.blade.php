@extends('layouts.app')

@section('title', 'Мои записи')

@section('content')
    <style>
        .appointments-wrapper {
            max-width: 1000px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        h1 {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        #appointmentsTable {
            width: 100%;
            border-collapse: collapse;
        }

        #appointmentsTable th, #appointmentsTable td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }

        #appointmentsTable th {
            background-color: #f9fafb;
            color: #374151;
        }

        .btn-icon {
            padding: 0.3rem 0.5rem;
            font-size: 0.875rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .btn-icon.edit {
            background-color: #3b82f6;
        }

        .btn-icon.delete {
            background-color: #ef4444;
        }

        .btn-icon.confirm {
            background-color: #10b981;
        }

        .btn-icon.cancel {
            background-color: #6b7280;
        }

        select, input[type="date"] {
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
            width: 100%;
        }

        .actions {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }

        .top-btn {
            text-align: center;
            margin-bottom: 1rem;
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
    </style>

    <div class="appointments-wrapper">
        <h1>Записи на приём</h1>

        <div class="top-btn">
            <button id="addAppointmentBtn">Добавить запись</button>
        </div>

        <table id="appointmentsTable">
            <thead>
            <tr>
                <th>Клиент</th>
                <th>Питомец</th>
                <th>Ветеринар</th>
                <th>Дата и время</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserId = {{ Auth::id() }};
        window.currentUserRole = '{{ Auth::user()->role }}';
        window.currentUserName = '{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}{{ Auth::user()->middle_name ? " " . Auth::user()->middle_name : "" }}';
    </script>
    @vite(['src/app.js'])
@endsection
