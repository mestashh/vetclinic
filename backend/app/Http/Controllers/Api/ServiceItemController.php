<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    public function index()
    {
        return response()->json(ServiceItem::all());
    }

    public function store(Request $request, $serviceId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer'
        ]);

        $validated['service_id'] = $serviceId;

        $item = ServiceItem::create($validated);

        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = ServiceItem::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = ServiceItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer'
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ServiceItem::findOrFail($id);
        $item->delete();

        return response()->noContent();
    }
}
