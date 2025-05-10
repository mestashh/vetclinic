<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('item.service')->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_item_id' => 'required|exists:service_items,id',
            'quantity' => 'required|integer|min:1',
            'comment' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $order = \App\Models\Order::create([
            'service_item_id' => $data['service_item_id'],
            'quantity'        => $data['quantity'],
            'comment'         => $data['comment'] ?? null,
            'price'           => $data['price']
        ]);


        \App\Models\ServiceItem::where('id', $data['service_item_id'])
            ->increment('quantity', $data['quantity']);

        return response()->json([
            'message' => 'Заявка создана и количество обновлено',
            'order' => $order
        ], 201);
    }




    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Заявка удалена']);
    }
}
