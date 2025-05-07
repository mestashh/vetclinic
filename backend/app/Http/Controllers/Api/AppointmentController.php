<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();

        return response()->json([
            'data' => $appointments
        ], 200);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'pet_id' => 'required|integer|exists:pets,id',
            'veterinarian_id' => 'required|integer|exists:veterinarians,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i:s',
            'notes' => 'nullable|string|max:255',
        ]);

        Appointment::create($data);

        return response()->json(['message' => 'Appointment created successfully'], 201);
    }


    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $data = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'pet_id' => 'required|integer|exists:pets,id',
            'veterinarian_id' => 'required|integer|exists:veterinarians,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i:s',
            'notes' => 'nullable|string|max:255',
        ]);

        $appointment->update($data);

        return response()->json(['message' => 'Запись совершена']);
    }


    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['message' => 'Удалено']);
    }
}
