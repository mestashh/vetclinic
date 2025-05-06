@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-4">Ветеринары</h1>

        <ul class="space-y-4">
            @forelse($veterinarians as $vet)
                <li class="border p-4 rounded shadow-sm">
                    <div class="text-xl font-semibold"><strong>ФИО:</strong>{{ $vet->full_name }}</div>
                    @if($vet->specialization)
                        <div><strong>Специальность:</strong> {{ $vet->specialization }}</div>
                    @endif
                    @if($vet->phone)
                        <div><strong>Телефон:</strong> {{ $vet->phone }}</div>
                    @endif
                    @if($vet->email)
                        <div><strong>Email:</strong> {{ $vet->email }}</div>
                    @endif
                </li>
            @empty
                <li>Ветеринаров пока нет.</li>
            @endforelse
        </ul>
    </div>
@endsection
