<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Veterinarian;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    public function index()
    {
        $vets = \App\Models\Veterinarian::with('user')->get()->map(function ($v) {
            return [
                'id'           => $v->id,
                'user'         => [
                    'first_name'  => $v->user?->first_name,
                    'middle_name' => $v->user?->middle_name,
                    'last_name'   => $v->user?->last_name,
                    'user_id' => $v->user_id,
                ]
            ];
        });

        return response()->json(['data' => $vets]);
    }

    public function byUser($userId)
    {
        $vet = \App\Models\Veterinarian::where('user_id', $userId)->first();

        if (!$vet) {
            return response()->json(['message' => 'Ветеринар не найден'], 404);
        }

        return response()->json([
            'id'        => $vet->id,
            'user_id'   => $vet->user_id,
            'full_name' => optional($vet->user)->last_name . ' ' . optional($vet->user)->first_name,
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'specialization'   => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|unique:veterinarians,email',
        ]);

        $veterinarian = Veterinarian::create($validated);
        return response()->json($veterinarian, 201);
    }

    public function update(Request $request, Veterinarian $veterinarian)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'specialty'   => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => "nullable|email|unique:veterinarians,email,{$veterinarian->id}",
        ]);

        $veterinarian->update($validated);
        return response()->json($veterinarian);
    }

    public function destroy(Veterinarian $veterinarian)
    {
        $veterinarian->delete();
        return response()->json(['message' => 'Ветеринар удалён'], 200);
    }
}
