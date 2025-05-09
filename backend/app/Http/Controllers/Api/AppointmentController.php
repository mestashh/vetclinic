<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{

    public function index(Request $request)
    {
        $now = Carbon::now();

        Appointment::where('scheduled_at', '<', $now)
            ->where('status', 'scheduled')
            ->update(['status' => 'missed']);

        $appointments = Appointment::with(['user', 'pet', 'veterinarian', 'services'])->get();

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'scheduled_at' => 'required|date',
            'status' => 'in:scheduled,completed,missed'
        ]);


        $appointment = Appointment::create($data);

        return response()->json($appointment, 201);
    }
    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'scheduled_at' => 'required|date',
            'status' => 'in:scheduled,completed,missed'
        ]);

        $appointment->update($data);

        return response()->json($appointment);
    }
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['message' => 'Запись удалена успешно']);
    }

    public function busySlots($veterinarian_id, $date)
    {
        $appointments = Appointment::where('veterinarian_id', $veterinarian_id)
            ->whereDate('scheduled_at', $date)
            ->pluck('scheduled_at')
            ->map(fn($slot) => (new \DateTime($slot))->format('H:i'))
            ->toArray();

        return response()->json(['busy_slots' => $appointments]);
    }

}
