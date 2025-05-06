@extends('layouts.app')

@section('title', 'Записи на приём')

@section('content')
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Записи на приём</h1>

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
                @forelse($appointments as $appointment)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $appointment->id }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</td>
                        <td class="px-4 py-2">{{ $appointment->pet->name }}</td>
                        <td class="px-4 py-2">{{ $appointment->veterinarian->full_name }}</td>
                        <td class="px-4 py-2">
                            @if($appointment->services->isNotEmpty())
                                {{ $appointment->services->pluck('name')->join(', ') }}
                            @else
                                Нет услуг
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ ucfirst($appointment->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-2 text-center" colspan="7">Записей пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
