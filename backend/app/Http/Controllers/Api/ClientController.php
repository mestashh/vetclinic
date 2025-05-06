<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('pages.clients', ['clients' => Client::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'email'       => 'nullable|email|unique:clients,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
        ]);
        $client = Client::create($data);
        return response()->json($client, 201);
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'email'       => "nullable|email|unique:clients,email,{$client->id}",
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
        ]);
        $client->update($data);
        return response()->json($client);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(['id' => $client->id]);
    }
}
