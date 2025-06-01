@extends('layouts.app')

@section('title', 'Ветеринары')

@section('content')
    <style>
        .vets-wrapper {
            max-width: 1000px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            font-family: sans-serif;
        }

        h1 {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }

        .action-buttons {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
            white-space: nowrap;
        }

        .btn-icon {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .confirm-btn { background-color: #10b981; }
        .cancel-btn  { background-color: #6b7280; }
        .edit-btn    { background-color: #3b82f6; }
        .delete-btn  { background-color: #ef4444; }

        .confirm-btn:hover { background-color: #059669; }
        .cancel-btn:hover  { background-color: #4b5563; }
        .edit-btn:hover    { background-color: #2563eb; }
        .delete-btn:hover  { background-color: #dc2626; }

        .top-btn {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .top-btn button {
            background-color: #2563eb;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        td.actions {
            width: 120px;
        }
    </style>

    <div class="vets-wrapper">
        <h1>Ветеринары</h1>
        <div class="overflow-x-auto">
            <table id="vetsTable">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Специализация</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th class="actions">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($veterinarians as $v)
                    <tr data-id="{{ $v->id }}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <input disabled value="{{ trim(optional($v->user)->last_name.' '.optional($v->user)->first_name.' '.optional($v->user)->middle_name) }}" class="vet-input w-full border-none">
                        </td>
                        <td class="px-4 py-2"><input disabled value="{{ $v->specialization ?? '' }}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="{{ $v->user->phone ?? '' }}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="{{ $v->user->email ?? '' }}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2 action-buttons">
                            <button class="edit-btn btn-icon">✏️</button>
                            <button class="delete-btn btn-icon">🗑️</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.initialVets = @json($veterinarians);
    </script>
    @vite(['src/app.js'])
@endsection
