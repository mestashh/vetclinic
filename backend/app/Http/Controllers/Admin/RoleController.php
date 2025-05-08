<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.roles.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:client,admin,vet,superadmin'
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('roles.index')->with('success', 'Роль обновлена');
    }
}
