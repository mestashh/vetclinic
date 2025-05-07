<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // 1) API: список клиентов в JSON
    public function index()
    {
        return response()->json(Client::all());
    }

    // 2) Web: возвращает Blade-страницу клиентов
    public function showClientsPage()
    {
        return view('pages.clients');
    }

    // 3) API: создание нового клиента
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'phone'       => ['nullable', 'regex:/^\+?[0-9]{10,15}$/'], // <-- телефон: 10–15 цифр, + в начале опционально
            'email'       => 'nullable|email',
            'address'     => 'nullable|string|max:255',
        ]);

        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'phone'       => ['nullable', 'regex:/^\+?[0-9]{10,15}$/'], // <-- то же правило
            'email'       => "nullable|email|unique:clients,email,{$client->id}",
            'address'     => 'nullable|string|max:255',
        ]);

        $client->update($validated);
        return response()->json($client);
    }

    // 5) API: удаление клиента
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(['message' => 'Клиент удалён'], 200);
    }
}
