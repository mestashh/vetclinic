@extends('layouts.app')

@section('title', 'Услуги')

@section('content')
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Услуги клиники</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Название</th>
                    <th class="px-4 py-2 text-left">Описание</th>
                    <th class="px-4 py-2 text-left">Цена</th>
                </tr>
                </thead>
                <tbody>
                @forelse($services as $service)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $service->id }}</td>
                        <td class="px-4 py-2">{{ $service->name }}</td>
                        <td class="px-4 py-2">{{ $service->description ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $service->price }}₽</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-2 text-center" colspan="4">Услуг пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
