@extends('layouts.app')

@section('title', 'Питомцы')

@section('content')
    <div class="layout-center">
        <div class="layout-container">

            <h1 style="text-align: center; font-size: 1.5rem; font-weight: bold;">Питомцы</h1>
            <div class="centered-btn">
                <button id="addPetBtn" class="btn-primary">Добавить питомца</button>
            </div>

            <div class="overflow-x-auto">
                <table id="petsTable" class="table-clean">
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

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pets.js') }}"></script>
    <script>initPets();</script>
@endsection
