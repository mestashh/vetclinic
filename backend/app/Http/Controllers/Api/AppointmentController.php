<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
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
        ]);

        $appointment->update($data);

        return response()->json($appointment);
    }
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['message' => 'Запись удалена успешно']);
    }

}
