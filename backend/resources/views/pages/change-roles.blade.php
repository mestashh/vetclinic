@extends('layouts.app')

@section('title', 'Изменение ролей пользователей')

@section('content')
    <style>
        .roles-wrapper {
            max-width: 1000px;
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
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .btn-icon {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .edit-btn    { background-color: #3b82f6; }
        .confirm-btn { background-color: #10b981; }
        .cancel-btn  { background-color: #6b7280; }

        .edit-btn:hover    { background-color: #2563eb; }
        .confirm-btn:hover { background-color: #059669; }
        .cancel-btn:hover  { background-color: #4b5563; }

        .action-buttons {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }
    </style>

    <div class="roles-wrapper">
        <h1>Изменение ролей пользователей</h1>
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <input type="text" id="searchInput" placeholder="Поиск по пользователям..."
                   style="width: 300px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px;" />
        </div>
        <table id="rolesTable">
            <thead>
            <tr>
                <th>ФИО</th>
                <th>Почта</th>
                <th>Телефон</th>
                <th>Редактировать</th>
            </tr>
            </thead>
            <tbody>
            {{-- JS вставит сюда строки --}}
            </tbody>
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
