<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // 1) API: список услуг в JSON
    public function index()
    {
        return response()->json(Service::all());
    }

    // 2) Web: возвращает Blade-страницу услуг
    public function showServicesPage()
    {
        return view('pages.services');
    }

    // 3) API: создание новой услуги
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    // 4) API: обновление услуги
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
        ]);

        $service->update($validated);
        return response()->json($service);
    }

    // 5) API: удаление услуги
    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Услуга удалена'], 200);
    }
}
