<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        return response()->json(Pet::with('client')->get());
    }

    public function store(Request $request)
    {
        $pet = Pet::create($request->all());
        return response()->json([
            'data' => $pet->load('client')
        ], 201);
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(null, 204);
    }
}
