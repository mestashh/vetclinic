@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-4">Записи на приём</h1>

        <ul class="space-y-4">
            @forelse($appointments as $appointment)
                <li class="border p-4 rounded shadow-sm">
                    <div><strong>Дата и время:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d.m.Y H:i') }}</div>
                    <div><strong>Клиент:</strong> {{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</div>
                    <div><strong>Питомец:</strong> {{ $appointment->pet->name }} ({{ $appointment->pet->species }})</div>
                    <div><strong>Ветеринар:</strong> {{ $appointment->veterinarian->first_name }} {{ $appointment->veterinarian->last_name }}</div>
                    <div><strong>Услуга:</strong> {{ $appointment->service->name }} — {{ $appointment->service->price }}₽</div>
                </li>
            @empty
                <li>Записей на приём пока нет.</li>
            @endforelse
        </ul>
    </div>
@endsection
