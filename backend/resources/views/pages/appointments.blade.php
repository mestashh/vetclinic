@extends('layouts.app')

@section('title', 'Приёмы')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Записи на приём</h1>

            @auth
                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <div class="centered-btn">
                        <button id="addAppointmentBtn" class="btn-primary">Добавить приём</button>
                    </div>
                @endif
            @endauth

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
        window.currentUserRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
    </script>
    @vite(['src/app.js'])
@endsection
