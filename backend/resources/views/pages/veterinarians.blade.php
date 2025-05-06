@extends('layouts.app')

@section('title', 'Ветеринары')

@section('content')
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Ветеринары</h1>

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
                        <td class="px-4 py-2">{{ $vet->full_name }}</td>
                        <td class="px-4 py-2">{{ $vet->specialization ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vet->phone ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vet->email ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-2 text-center" colspan="5">Ветеринаров пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
