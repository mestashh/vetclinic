@extends('layouts.app')

@section('title', 'Питомцы')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Питомцы</h2>
        <button
            id="addPetBtn"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table
            id="petsTable"
            class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Имя</th>
                <th class="px-4 py-2">Вид</th>
                <th class="px-4 py-2">Владелец</th>
                <th class="px-4 py-2">Действия</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection
