@extends('layouts.app')

@section('title', 'Ветеринары')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Ветеринары</h2>
        <button
            id="addVeterinarianBtn"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table
            id="vetsTable"
            class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Фамилия</th>
                <th class="px-4 py-2">Имя</th>
                <th class="px-4 py-2">Отчество</th>
                <th class="px-4 py-2">Действия</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection
