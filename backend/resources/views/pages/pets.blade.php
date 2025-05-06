@extends('layouts.app')

@section('title', 'Питомцы')

@section('content')
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Список питомцев</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Имя</th>
                    <th class="px-4 py-2 text-left">Вид</th>
                    <th class="px-4 py-2 text-left">Порода</th>
                    <th class="px-4 py-2 text-left">Дата рождения</th>
                    <th class="px-4 py-2 text-left">Владелец</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pets as $pet)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $pet->id }}</td>
                        <td class="px-4 py-2">{{ $pet->name }}</td>
                        <td class="px-4 py-2">{{ $pet->species }}</td>
                        <td class="px-4 py-2">{{ $pet->breed }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($pet->birth_date)->format('d.m.Y') }}</td>
                        <td class="px-4 py-2">{{ $pet->client->first_name }} {{ $pet->client->last_name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-2 text-center" colspan="6">Питомцев пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
