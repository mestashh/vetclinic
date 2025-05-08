<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'data' => \App\Models\User::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'required|email|unique:users,email',
            'address'     => 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt('password');
        $validated['role'] = 'client';

        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'email'       => "required|email|unique:users,email,{$user->id}",
            'address'     => 'nullable|string|max:255',
        ]);

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Клиент удалён'], 200);
    }
    public function pets($userId)
    {
        $user = User::with('pets')->findOrFail($userId);
        return response()->json(['data' => $user->pets]);
    }

}
