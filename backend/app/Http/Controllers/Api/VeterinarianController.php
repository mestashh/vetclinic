<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Veterinarian;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => \App\Models\Veterinarian::all()
        ]);
    }

    public function showVeterinariansPage()
    {
        return view('pages.veterinarians');
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
