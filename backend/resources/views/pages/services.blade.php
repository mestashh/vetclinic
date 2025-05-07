@extends('layouts.app')

@section('title', 'Услуги')

@section('content')
    <div class="layout-center">
        <div class="layout-container">

            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Услуги</h1>
            <div class="centered-btn">
                <button id="addServiceBtn" class="btn-primary">Добавить услугу</button>
            </div>

            <div class="overflow-x-auto">
                <table id="servicesTable" class="table-clean">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
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
    <script src="{{ asset('js/services.js') }}"></script>
    <script>initServices();</script>
@endsection
