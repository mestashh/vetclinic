<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        switch ($user->role) {
            case 'superadmin':
            case 'admin':
                $appointments = Appointment::with(['client', 'pet', 'veterinarian', 'services'])->get();
                break;

            case 'vet':
                $appointments = Appointment::with(['client', 'pet', 'veterinarian', 'services'])
                    ->where('veterinarian_id', $user->id)->get();
                break;

            case 'client':
                $appointments = Appointment::with(['client', 'pet', 'veterinarian', 'services'])
                    ->where('client_id', $user->id)->get();
                break;

            default:
                return response()->json(['message' => 'Недостаточно прав'], 403);
        }

        return response()->json($appointments);
    }
}
