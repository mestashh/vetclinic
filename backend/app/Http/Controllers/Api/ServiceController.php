<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('pages.services', ['services' => Service::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
        ]);
        $svc = Service::create($data);
        return response()->json($svc, 201);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
        ]);
        $service->update($data);
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['id' => $service->id]);
    }
}
