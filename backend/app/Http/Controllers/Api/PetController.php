<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    // 1) API: список питомцев в JSON
    public function index()
    {
        return response()->json(Pet::all());
    }

    // 2) Web: возвращает Blade-страницу питомцев
    public function showPetsPage()
    {
        return view('pages.pets');
    }

    // 3) API: создание нового питомца
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'client_id' => 'required|exists:clients,id'
        ]);

        $pet = Pet::create($validated);
        return response()->json($pet, 201);
    }

    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'client_id' => 'required|exists:clients,id'
        ]);

        $pet->update($validated);
        return response()->json($pet);
    }

    // 5) API: удаление питомца
    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(['message' => 'Питомец удалён'], 200);
    }
}
