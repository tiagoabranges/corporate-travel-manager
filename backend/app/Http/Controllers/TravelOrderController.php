<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Notifications\TravelOrderStatusNotification;

class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = TravelOrder::query()
            ->where('user_id', auth()->id())
            ->filters($request->all())
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function store(StoreTravelOrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'requested';

        $order = TravelOrder::create($data);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        $data = $request->validate([
            'requester_name' => 'sometimes|string',
            'destination' => 'sometimes|string',
            'departure_date' => 'sometimes|date',
            'return_date' => 'sometimes|date|after_or_equal:departure_date'
        ]);

        $order->update($data);

        return response()->json($order);
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $order = TravelOrder::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Apenas administradores podem alterar status'
            ], 403);
        }

        if ($order->status === 'approved' && $request->status === 'cancelled') {
            return response()->json([
                'message' => 'Pedido já aprovado não pode ser cancelado'
            ], 422);
        }

        $order->update([
            'status' => $request->status
        ]);

        $order->user->notify(
            new TravelOrderStatusNotification($order)
        );

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        $order->delete();

        return response()->json([
            'message' => 'Pedido removido com sucesso'
        ]);
    }
}
