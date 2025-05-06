@extends('layouts.app')
@section('title','Записи на приём')
@section('content')
    <div class="p-4">

        <form method="GET" action="{{ route('appointments') }}" class="mb-4">
            <div class="flex items-center">
                <label class="mr-2">Дата:</label>
                <input type="date" name="date" value="{{ $date ?? '' }}"
                       class="border rounded p-2 mr-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Поиск</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Дата и время</th>
                    <th class="px-4 py-2 text-left">Клиент</th>
                    <th class="px-4 py-2 text-left">Питомец</th>
                    <th class="px-4 py-2 text-left">Ветеринар</th>
                    <th class="px-4 py-2 text-left">Услуги</th>
                    <th class="px-4 py-2 text-left">Статус</th>
                </tr>
                </thead>
                <tbody>
                @forelse($appointments as $a)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $a->id }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($a->scheduled_at)->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $a->client->first_name }} {{ $a->client->last_name }}</td>
                        <td class="px-4 py-2">{{ $a->pet->name }}</td>
                        <td class="px-4 py-2">{{ $a->veterinarian->first_name }} {{ $a->veterinarian->last_name }}</td>
                        <td class="px-4 py-2">
                            @if($a->services->isNotEmpty())
                                {{ $a->services->pluck('name')->join(', ') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $a->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center">Записей пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
