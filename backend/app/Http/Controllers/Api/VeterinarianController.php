<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Veterinarian;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    public function index()
    {
        return view('pages.veterinarians', ['veterinarians' => Veterinarian::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'specialty'   => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|unique:veterinarians,email',
        ]);
        $vet = Veterinarian::create($data);
        return response()->json($vet, 201);
    }

    public function update(Request $request, Veterinarian $veterinarian)
    {
        $data = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'specialty'   => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => "nullable|email|unique:veterinarians,email,{$veterinarian->id}",
        ]);
        $veterinarian->update($data);
        return response()->json($veterinarian);
    }

    public function destroy(Veterinarian $veterinarian)
    {
        $veterinarian->delete();
        return response()->json(['id' => $veterinarian->id]);
    }
}
