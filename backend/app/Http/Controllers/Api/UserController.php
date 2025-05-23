<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:client,vet,admin,superadmin',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        // Если новая роль — vet и записи в veterinarians ещё нет, создаём её
        if ($user->role === 'vet' && !$user->veterinarian) {
            \App\Models\Veterinarian::create([
                'user_id' => $user->id,
                'specialization' => 'Ветеринар',
                'phone' => $user->phone,
                'email' => $user->email,
            ]);
        }

        return response()->json(['message' => 'Роль обновлена']);
    }

    public function veterinarians()
    {
        $vets = User::where('role', 'vet')->get();

        $result = $vets->map(function ($user) {
            return [
                'id' => $user->id,
                'specialization' => optional($user->veterinarian)->specialization ?? '',
                'user' => [
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'user_id' => $user->id,
                ]
            ];
        });

        return response()->json(['data' => $result]);
    }

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
            'passport' => 'nullable|string|max:255',
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
            'passport' => 'nullable|string|max:255',
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
