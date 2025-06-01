@extends('layouts.app')

@section('title', 'Приёмы')

@section('content')
    <style>
        .appointments-wrapper {
            max-width: 1200px;
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

        .action-buttons {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }

        .btn-icon {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .edit-btn { background-color: #3b82f6; }
        .delete-btn { background-color: #ef4444; }
        .confirm-btn { background-color: #10b981; }
        .cancel-btn { background-color: #6b7280; }

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

        input[type="text"], input[type="number"], input[type="datetime-local"], select {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }
    </style>

    <div class="appointments-wrapper">
        <h1>Приёмы</h1>
        @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
            <div class="top-btn">
                <button id="addAppointmentBtn">Добавить приём</button>
            </div>
        @endif
        <div style="margin-bottom: 1rem; text-align: center;">
            <input type="text" id="searchInput" placeholder="Поиск по приёмам..."
                   style="width: 300px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px;" />
        </div>
        @php
            $timeSlots = ['10:00','11:30','13:00','14:30','16:00','17:30'];
            $statusMap = ['scheduled' => 'Запланирован', 'completed' => 'Проведён', 'missed' => 'Не проведён'];
        @endphp
        <table id="appointmentsTable">
            <thead>
            <tr>
                <th>Клиент</th>
                <th>Питомец</th>
                <th>Ветеринар</th>
                <th>Дата и время</th>
                <th>Статус</th>
                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <th>Действия</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($appointments as $a)
                @php
                    $slot = new \Carbon\Carbon($a->scheduled_at);
                    $dateVal = $slot->format('Y-m-d');
                    $timeVal = $slot->format('H:i');
                @endphp
                <tr data-id="{{ $a->id }}" class="border-b">
                    <td class="px-2 py-2">
                        <select class="user-select w-full border-none" disabled>
                            <option value="">Клиент</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected($a->client_id == $u->id)>
                                    {{ trim($u->last_name.' '.$u->first_name.' '.$u->middle_name) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-2 py-2">
                        <select class="pet-select w-full border-none" disabled>
                            <option value="">Питомец</option>
                            @foreach($pets as $p)
                                <option value="{{ $p->id }}" @selected($a->pet_id == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-2 py-2">
                        <select class="vet-select w-full border-none" disabled>
                            <option value="">Ветеринар</option>
                            @foreach($veterinarians as $v)
                                <option value="{{ $v->id }}" @selected($a->veterinarian_id == $v->id)>
                                    {{ trim(optional($v->user)->last_name.' '.optional($v->user)->first_name) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-2 py-2">
                        <input type="date" class="date-input w-full border-none" disabled value="{{ $dateVal }}" min="{{ now()->format('Y-m-d') }}">
                        <select class="time-select w-full border-none" disabled>
                            @foreach($timeSlots as $slotTime)
                                <option value="{{ $slotTime }}" @selected($timeVal == $slotTime)>{{ $slotTime }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-2 py-2"><span>{{ $statusMap[$a->status] ?? '—' }}</span></td>
                    @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                        <td class="px-2 py-2 actions">
                            <button class="edit-btn btn-icon edit">✏️</button>
                            <button class="delete-btn btn-icon delete">🗑️</button>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = '{{ Auth::user()->role }}';
        window.currentUserId = {{ Auth::id() }};
        window.initialAppointments = @json($appointments);
        window.initialUsers = @json($users);
        window.initialVets = @json($veterinarians);
        window.initialPets = @json($pets);
    </script>
    @vite(['src/app.js'])
@endsection
