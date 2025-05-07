@extends('layouts.app')

@section('title', 'Клиенты')

@section('content')
    <div class="layout-center">
        <div class="layout-container">

            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Клиенты</h1>
            <div class="centered-btn">
                <button id="addClientBtn" class="btn-primary">Добавить клиента</button>
            </div>

            <div class="overflow-x-auto">
                <table id="clientsTable" class="table-clean">
                    <thead>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Адрес</th>
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
    <script src="{{ asset('js/clients.js') }}"></script>
    <script>initClients();</script>
@endsection
