@extends('layouts.app')

@section('title', 'Обо мне')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 class="text-xl font-bold text-center mb-4">Обо мне</h1>

            {{-- Сообщение об успехе --}}
            @if(session('success'))
                <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
            @endif

            {{-- Данные пользователя --}}
            <form id="userForm">
                @csrf
                <table class="table-clean">
                    <tr><th>Имя</th><td><input disabled class="user-input w-full" name="first_name" value="{{ $user->first_name }}"></td></tr>
                    <tr><th>Фамилия</th><td><input disabled class="user-input w-full" name="last_name" value="{{ $user->last_name }}"></td></tr>
                    <tr><th>Отчество</th><td><input disabled class="user-input w-full" name="middle_name" value="{{ $user->middle_name }}"></td></tr>
                    <tr><th>Email</th><td><input disabled class="user-input w-full" name="email" value="{{ $user->email }}"></td></tr>
                    <tr><th>Телефон</th><td><input disabled class="user-input w-full" name="phone" value="{{ $user->phone }}"></td></tr>
                    <tr><th>Адрес</th><td><input disabled class="user-input w-full" name="address" value="{{ $user->address }}"></td></tr>
                </table>

                <div class="centered-btn mt-4">
                    <button type="button" id="editBtn" class="btn-primary">Редактировать</button>
                    <button type="button" id="saveBtn" class="btn-primary" style="display:none;">Сохранить</button>
                </div>
            </form>

            {{-- Питомцы --}}
            <h2 class="text-lg font-semibold mt-6 mb-2">Все питомцы</h2>
            <table id="petsTable" class="table-clean">
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
                {{-- Данные вставит JS --}}
                </tbody>
            </table>

            {{-- Кнопка для добавления питомца --}}
            <div class="centered-btn mt-4">
                <button type="button" id="addPetBtn" class="btn-primary">Добавить питомца</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserId = {{ Auth::id() }};
    </script>
    @vite(['src/app.js'])
@endsection
