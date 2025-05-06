<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('pages.appointments', [
            'appointments' => Appointment::with(['client','pet','veterinarian','services'])->get(),
            'clients'      => \App\Models\Client::all(),
            'pets'         => \App\Models\Pet::all(),
            'vets'         => \App\Models\Veterinarian::all(),
            'services'     => \App\Models\Service::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'scheduled_at'    => 'required|date',
            'client_id'       => 'required|exists:clients,id',
            'pet_id'          => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'services'        => 'array',
            'services.*'      => 'exists:services,id',
            'status'          => 'required|string',
        ]);
        $appt = Appointment::create($request->only(['scheduled_at','client_id','pet_id','veterinarian_id','status']));
        $appt->services()->sync($data['services'] ?? []);
        $appt->load(['client','pet','veterinarian','services']);
        return response()->json($appt, 201);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'scheduled_at'    => 'required|date',
            'client_id'       => 'required|exists:clients,id',
            'pet_id'          => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'services'        => 'array',
            'services.*'      => 'exists:services,id',
            'status'          => 'required|string',
        ]);
        $appointment->update($request->only(['scheduled_at','client_id','pet_id','veterinarian_id','status']));
        $appointment->services()->sync($data['services'] ?? []);
        $appointment->load(['client','pet','veterinarian','services']);
        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['id' => $appointment->id]);
    }
}
