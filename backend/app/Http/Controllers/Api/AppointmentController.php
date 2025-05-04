<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::with(['client','pet','veterinarian','services'])->get();
    }

    public function show(Appointment $appointment)
    {
        return $appointment->load(['client','pet','veterinarian','services']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'pet_id'          => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'scheduled_at'    => 'required|date',
            'status'          => 'sometimes|string',
        ]);

        $appointment = Appointment::create($data);

        return response()->json($appointment, 201);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'client_id'       => 'sometimes|required|exists:clients,id',
            'pet_id'          => 'sometimes|required|exists:pets,id',
            'veterinarian_id' => 'sometimes|required|exists:veterinarians,id',
            'scheduled_at'    => 'sometimes|required|date',
            'status'          => 'sometimes|string',
        ]);

        $appointment->update($data);

        return $appointment;
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->noContent();
    }
}
