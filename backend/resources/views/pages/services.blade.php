@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-4">Услуги клиники</h1>

        <ul class="space-y-4">
            @forelse($services as $service)
                <li class="border p-4 rounded shadow-sm">
                    <div class="text-xl font-semibold">{{ $service->name }}</div>
                    @if($service->description)
                        <div class="mt-1">{{ $service->description }}</div>
                    @endif
                    <div class="mt-2"><strong>Цена:</strong> {{ $service->price }}₽</div>
                </li>
            @empty
                <li>Услуг пока нет.</li>
            @endforelse
        </ul>
    </div>
@endsection
