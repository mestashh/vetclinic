@extends('layouts.app')

@section('title', 'Записи на приём')

@section('content')
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-4">Записи на приём</h1>

        <ul class="space-y-4">
            @forelse($appointments as $appointment)
                <li class="border p-4 rounded shadow-sm">
                    <div>
                        <strong>Дата и время:</strong>
                        {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d.m.Y H:i') }}
                    </div>
                    <div>
                        <strong>Клиент:</strong>
                        {{ $appointment->client->first_name }} {{ $appointment->client->last_name }}
                    </div>
                    <div>
                        <strong>Питомец:</strong>
                        {{ $appointment->pet->name }} ({{ $appointment->pet->species }})
                    </div>
                    <div>
                        <strong>Ветеринар:</strong>
                        {{ $appointment->veterinarian->full_name }}
                    </div>
                    <div>
                        <strong>Услуги:</strong>
                        @if($appointment->services->isNotEmpty())
                            @foreach($appointment->services as $service)
                                {{ $service->name }} ({{ $service->price }}₽)@if(! $loop->last), @endif
                            @endforeach
                        @else
                            <span>Нет назначенных услуг</span>
                        @endif
                    </div>
                    <div>
                        <strong>Статус:</strong> {{ $appointment->status }}
                    </div>
                </li>
            @empty
                <li>Записей на приём пока нет.</li>
            @endforelse
        </ul>
    </div>
@endsection
