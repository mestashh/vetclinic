<!-- vetclinic/backend/resources/views/appointments.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-4">
        <h1 class="text-2xl font-bold mb-4">Записи на приём</h1>

        <button id="addAppointmentBtn" class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Добавить запись
        </button>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table id="appointmentsTable" class="min-w-full text-sm text-left">
                <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2">Клиент</th>
                    <th class="px-4 py-2">Питомец</th>
                    <th class="px-4 py-2">Ветеринар</th>
                    <th class="px-4 py-2">Дата и время</th>
                    <th class="px-4 py-2">Примечание</th>
                    <th class="px-4 py-2">Действия</th>
                </tr>
                </thead>
                <tbody>
                <!-- Динамически заполняется через JS -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('frontend/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof initAppointments === 'function') {
                initAppointments();
            } else {
                console.error('Функция initAppointments не найдена. Убедись, что она экспортируется в main.js');
            }
        });
    </script>
@endsection
