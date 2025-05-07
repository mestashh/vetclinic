<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Client::all()
        ]);
    }

    public function store(Request $request)
    {
        $client = Client::create($request->all());

        return response()->json([
            'data' => $client
        ], 201);
    }

    public function show(Client $client)
    {
        return response()->json([
            'data' => $client
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $client->update($request->all());

        return response()->json([
            'data' => $client
        ]);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(null, 204);
    }

    /**
     * Вернуть всех питомцев клиента по ID
     */
    public function pets($client_id)
    {
        $client = Client::findOrFail($client_id);

        return response()->json([
            'data' => $client->pets()->get()
        ]);
    }
}
