@extends('layouts.app')
@section('title','Ветеринары')
@section('content')
    <div class="p-4">

        <form method="GET" action="{{ route('veterinarians') }}" class="mb-4">
            <div class="flex">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Поиск по ФИО или специализации" class="border rounded p-2 flex-grow mr-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Поиск</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">ФИО</th>
                    <th class="px-4 py-2 text-left">Специальность</th>
                    <th class="px-4 py-2 text-left">Телефон</th>
                    <th class="px-4 py-2 text-left">Email</th>
                </tr>
                </thead>
                <tbody>
                @forelse($veterinarians as $vet)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $vet->id }}</td>
                        <td class="px-4 py-2">{{ $vet->first_name }} {{ $vet->last_name }}</td>
                        <td class="px-4 py-2">{{ $vet->specialty ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vet->phone ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vet->email ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center">Ветеринаров пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
