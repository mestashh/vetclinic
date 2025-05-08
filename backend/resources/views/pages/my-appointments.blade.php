@extends('layouts.app')

@section('title', 'Записи на приём')

@section('content')
    <div class="layout-center">
        <div class="layout-container">

            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Записи на приём</h1>
            <div class="centered-btn">
                <button id="addAppointmentBtn" class="btn-primary">Добавить запись</button>
            </div>

            <div class="overflow-x-auto">
                <table id="appointmentsTable" class="table-clean">
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

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserId = {{ Auth::id() }};
        window.currentUserRole = '{{ Auth::user()->role }}';
        window.currentUserName = '{{ Auth::user()->full_name }}';
    </script>
    @vite(['src/app.js'])
@endsection
