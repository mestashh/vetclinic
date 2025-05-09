<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AppointmentController extends Controller
{
    public function complete(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'service_item_ids' => 'array',
            'service_item_ids.*' => 'exists:service_items,id',
            'comment' => 'nullable|string'
        ]);

        DB::transaction(function () use ($appointment, $data) {
            $appointment->status = 'completed';
            if (isset($data['comment'])) {
                $appointment->comment = $data['comment']; // если поле добавлено в таблицу
            }
            $appointment->save();

            // Обновим связи с услугами
            $appointment->services()->detach();

            if (!empty($data['service_item_ids'])) {
                // Получим ID услуг по переданным service_item_ids
                $serviceIds = DB::table('service_items')
                    ->whereIn('id', $data['service_item_ids'])
                    ->pluck('service_id')
                    ->unique()
                    ->values();

                $appointment->services()->sync($serviceIds);
            }
        });

        return response()->json(['message' => 'Приём завершён успешно']);
    }


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
