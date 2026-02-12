<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;


class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = TravelOrder::query()
            ->filters($request->all())
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'required|date',
            'status' => 'required|string'
        ]);

        $data['user_id'] = auth()->id();

        $order = TravelOrder::create($data);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = TravelOrder::findOrFail($id);

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = TravelOrder::findOrFail($id);

        $data = $request->validate([
            'requester_name' => 'sometimes|string',
            'destination' => 'sometimes|string',
            'departure_date' => 'sometimes|date',
            'return_date' => 'sometimes|date',
            'status' => 'sometimes|string'
        ]);

        $order->update($data);

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = TravelOrder::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
