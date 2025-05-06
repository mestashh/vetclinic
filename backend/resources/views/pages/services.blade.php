@extends('layouts.app')
@section('title','Услуги клиники')
@section('content')
    <div class="p-4">

        <form method="GET" action="{{ route('services') }}" class="mb-4">
            <div class="flex">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Поиск по названию услуги" class="border rounded p-2 flex-grow mr-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Поиск</button>
            </div>
        </form>

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
                        <td colspan="4" class="px-4 py-2 text-center">Услуг пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
