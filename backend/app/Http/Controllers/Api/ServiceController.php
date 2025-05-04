<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::all();
    }

    public function show(Service $service)
    {
        return $service;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
        ]);

        $svc = Service::create($data);

        return response()->json($svc, 201);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'        => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
            'price'       => 'sometimes|required|numeric',
        ]);

        $service->update($data);

        return $service;
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->noContent();
    }
}
