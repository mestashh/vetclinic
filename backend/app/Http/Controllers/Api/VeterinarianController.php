<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Veterinarian;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    public function index()
    {
        return Veterinarian::all();
    }

    public function show(Veterinarian $veterinarian)
    {
        return $veterinarian;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'      => 'required|string',
            'specialization' => 'nullable|string',
            'phone'          => 'nullable|string',
            'email'          => 'required|email|unique:veterinarians,email',
        ]);

        $vet = Veterinarian::create($data);

        return response()->json($vet, 201);
    }

    public function update(Request $request, Veterinarian $veterinarian)
    {
        $data = $request->validate([
            'full_name'      => 'sometimes|required|string',
            'specialization' => 'sometimes|nullable|string',
            'phone'          => 'sometimes|nullable|string',
            'email'          => "sometimes|required|email|unique:veterinarians,email,{$veterinarian->id}",
        ]);

        $veterinarian->update($data);

        return $veterinarian;
    }

    public function destroy(Veterinarian $veterinarian)
    {
        $veterinarian->delete();

        return response()->noContent();
    }
}
