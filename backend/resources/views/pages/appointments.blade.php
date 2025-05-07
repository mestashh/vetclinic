{{-- resources/views/pages/appointments.blade.php --}}
@extends('layouts.app')

@section('title', 'Приёмы')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Приёмы</h2>
        <button id="addAppointmentBtn" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table id="appointmentsTable" class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Клиент ID</th>
                <th class="px-4 py-2">Питомец ID</th>
                <th class="px-4 py-2">Ветеринар ID</th>
                <th class="px-4 py-2">Дата/время</th>
                <th class="px-4 py-2">Примечание</th>
                <th class="px-4 py-2">Действия</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @vite('src/pages/appointments.js')

@endsection
