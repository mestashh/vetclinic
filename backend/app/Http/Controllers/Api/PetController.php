<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        return view('pages.pets', [
            'pets'    => Pet::with('client')->get(),
            'clients' => \App\Models\Client::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'species'    => 'required|string|max:50',
            'breed'      => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'client_id'  => 'required|exists:clients,id',
        ]);
        $pet = Pet::create($data);
        $pet->load('client');
        return response()->json($pet, 201);
    }

    public function update(Request $request, Pet $pet)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'species'    => 'required|string|max:50',
            'breed'      => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'client_id'  => 'required|exists:clients,id',
        ]);
        $pet->update($data);
        $pet->load('client');
        return response()->json($pet);
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(['id' => $pet->id]);
    }
}
