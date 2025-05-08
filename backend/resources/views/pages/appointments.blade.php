@extends('layouts.app')

@section('title', 'Записи на приём')

@section('content')
    <div class="layout-center">
        <div class="layout-container">

            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Приемы</h1>

            <div class="overflow-x-auto">
                <table id="appointmentsTable" class="table-clean">
                    <thead>
                    <tr>
                        <th>Клиент</th>
                        <th>Питомец</th>
                        <th>Ветеринар</th>
                        <th>Дата и время</th>
                        @if (in_array(Auth::user()->role, ['admin', 'superadmin']))
                            <th>Действия</th>
                        @endif
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
