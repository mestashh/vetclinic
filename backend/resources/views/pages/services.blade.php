@extends('layouts.app')

@section('title', 'Услуги')

@section('content')
    <div class="layout-center">
        <div class="layout-container">
            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Услуги</h1>

            @auth
                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <div class="centered-btn">
                        <button id="addServiceBtn" class="btn-primary">Добавить услугу</button>
                    </div>
                @endif
            @endauth

            <div class="overflow-x-auto">
                <table id="servicesTable" class="table-clean">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
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
        window.currentUserRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
    </script>
    @vite(['src/app.js'])
@endsection
