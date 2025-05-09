<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with('client')->get();

        return response()->json([
            'data' => $pets
        ]);
    }


    /**
     * Создает нового питомца.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'species'   => 'required|string|max:255',
            'breed'     => 'nullable|string|max:255',
            'age'       => 'nullable|integer|min:0|max:100',
            'client_id' => 'required|integer|exists:users,id',
        ]);

        $pet = Pet::create($validated);

        return response()->json([
            'data' => $pet
        ], 201);
    }

    /**
     * Обновляет питомца.
     */
    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'client_id' => 'required|integer|exists:users,id',
        ]);

        $pet->update($validated);

        return response()->json(['data' => $pet]);
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(null, 204);
    }
}
