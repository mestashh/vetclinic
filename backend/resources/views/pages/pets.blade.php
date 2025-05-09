@extends('layouts.app')

@section('title', 'Питомцы')

@section('content')
    <style>
        .pets-wrapper {
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
            white-space: nowrap;
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
        .edit-btn:hover { background-color: #2563eb; }

        .delete-btn { background-color: #ef4444; }
        .delete-btn:hover { background-color: #dc2626; }

        .confirm-btn { background-color: #10b981; }
        .confirm-btn:hover { background-color: #059669; }

        .cancel-btn { background-color: #6b7280; }
        .cancel-btn:hover { background-color: #4b5563; }

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

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }
    </style>

    <div class="pets-wrapper">
        <h1>Питомцы</h1>

        <div class="top-btn">
            <button id="addPetBtn">Добавить питомца</button>
        </div>
        <div style="margin-bottom: 1rem; text-align: center;">
            <input type="text" id="searchInput" placeholder="Поиск по питомцам..."
                   style="width: 300px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px;" />
        </div>
        <table id="petsTable">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Вид</th>
                <th>Порода</th>
                <th>Возраст</th>
                <th>Владелец</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('scripts')
    @vite(['src/app.js'])
@endsection
