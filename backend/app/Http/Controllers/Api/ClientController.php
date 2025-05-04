<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::select([
            'id','first_name','middle_name','last_name',
            'phone','email','address','created_at','updated_at'
        ])->get();
    }

    public function show(Client $client)
    {
        return $client->only([
            'id','first_name','middle_name','last_name',
            'phone','email','address','created_at','updated_at'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'  => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name'   => 'required|string',
            'phone'       => 'nullable|string',
            'email'       => 'nullable|email',
            'address'     => 'nullable|string',
        ]);

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'first_name'  => 'sometimes|required|string',
            'middle_name' => 'sometimes|nullable|string',
            'last_name'   => 'sometimes|required|string',
            'phone'       => 'sometimes|nullable|string',
            'email'       => "sometimes|nullable|email",
            'address'     => 'sometimes|nullable|string',
        ]);

        $client->update($data);

        return $client->only([
            'id','first_name','middle_name','last_name',
            'phone','email','address','created_at','updated_at'
        ]);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->noContent();
    }
}
