<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        return Pet::with('client')->get();
    }

    public function show(Pet $pet)
    {
        return $pet->load('client');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string',
            'species'    => 'required|string',
            'breed'      => 'nullable|string',
            'birth_date' => 'nullable|date',
            'client_id'  => 'required|exists:clients,id',
        ]);

        $pet = Pet::create($data);

        return response()->json($pet, 201);
    }

    public function update(Request $request, Pet $pet)
    {
        $data = $request->validate([
            'name'       => 'sometimes|required|string',
            'species'    => 'sometimes|required|string',
            'breed'      => 'sometimes|nullable|string',
            'birth_date' => 'sometimes|nullable|date',
            'client_id'  => 'sometimes|required|exists:clients,id',
        ]);

        $pet->update($data);

        return $pet;
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();

        return response()->noContent();
    }
}
