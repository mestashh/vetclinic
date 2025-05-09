@extends('layouts.app')

@section('title', 'Обо мне')

@section('content')
    <style>

        .profile-section {
            max-width: 720px;
            margin: 2rem auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .profile-section h1,
        .profile-section h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }

        .profile-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .profile-table th {
            text-align: left;
            padding: 0.5rem;
            width: 30%;
            color: #374151;
            font-weight: 500;
        }

        .profile-table td {
            padding: 0.5rem;
        }

        .profile-table input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }

        .btn-row {
            text-align: center;
            margin-bottom: 2rem;
        }

        .btn-row button {
            padding: 0.5rem 1.5rem;
            background-color: #2563eb;
            border: none;
            border-radius: 4px;
            color: white;
            margin: 0 0.5rem;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-row button:hover {
            background-color: #1e40af;
        }

        .pets-section {
            margin-top: 3rem;
        }

        .pets-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .pets-table th, .pets-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        .pets-table th {
            background-color: #f3f4f6;
            color: #374151;
        }

        .pet-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .icon-button {
            padding: 0.4rem 0.6rem;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .icon-edit {
            background-color: #3b82f6;
        }

        .icon-edit:hover {
            background-color: #2563eb;
        }

        .icon-delete {
            background-color: #ef4444;
        }

        .icon-delete:hover {
            background-color: #dc2626;
        }

        .add-pet-btn {
            display: block;
            margin: 1.5rem auto 0;
            background-color: #10b981;
            padding: 0.5rem 1.5rem;
            border: none;
            color: white;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-pet-btn:hover {
            background-color: #059669;
        }

        .success-message {
            color: #059669;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        .pet-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .icon-button {
            padding: 0.3rem 0.5rem;
            font-size: 0.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .icon-edit {
            background-color: #3b82f6;
        }
        .icon-edit:hover {
            background-color: #2563eb;
        }

        .icon-delete {
            background-color: #ef4444;
        }
        .icon-delete:hover {
            background-color: #dc2626;
        }

    </style>

    <div class="profile-section">
        <h1>Обо мне</h1>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <form id="userForm">
            @csrf
            <table class="profile-table">
                <tr><th>Имя</th><td><input disabled name="first_name" value="{{ $user->first_name }}"></td></tr>
                <tr><th>Фамилия</th><td><input disabled name="last_name" value="{{ $user->last_name }}"></td></tr>
                <tr><th>Отчество</th><td><input disabled name="middle_name" value="{{ $user->middle_name }}"></td></tr>
                <tr><th>Email</th><td><input disabled name="email" value="{{ $user->email }}"></td></tr>
                <tr><th>Телефон</th><td><input disabled name="phone" value="{{ $user->phone }}"></td></tr>
                <tr><th>Адрес</th><td><input disabled name="address" value="{{ $user->address }}"></td></tr>
            </table>

            <div class="btn-row">
                <button type="button" id="editBtn">Редактировать</button>
                <button type="button" id="saveBtn" style="display:none;">Сохранить</button>
            </div>
        </form>

        <div class="pets-section">
            <h2>Все питомцы</h2>

            <table id="petsTable" class="pets-table">
                <thead>
                <tr>
                    <th>Кличка</th>
                    <th>Вид</th>
                    <th>Порода</th>
                    <th>Возраст</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                {{-- сюда вставляет JS --}}
                </tbody>
            </table>

            <button type="button" id="addPetBtn" class="add-pet-btn">Добавить питомца</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserId = {{ Auth::id() }};
    </script>
    @vite(['src/app.js'])
@endsection
