@extends('layouts.app')

@section('title', 'Врачи')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Ветеринары</h1>

            <div class="centered-btn">
                <button id="addVetBtn" class="btn-primary">Добавить врача</button>
            </div>

            <div class="overflow-x-auto">
                <table id="vetsTable" class="table-clean">
                    <thead>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Специализация</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
    </script>
    @vite(['src/app.js'])
@endsection
