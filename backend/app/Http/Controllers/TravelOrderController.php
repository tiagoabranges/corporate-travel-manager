<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder;

class TravelOrderController extends Controller
{
    /**
     * Listar pedidos
     */
    public function index()
    {
        $orders = TravelOrder::all();

        return response()->json($orders);
    }

    /**
     * Criar pedido
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'requester_name' => 'required|string',
        'destination' => 'required|string',
        'departure_date' => 'required|date',
        'return_date' => 'required|date|after_or_equal:departure_date',
        'user_id' => 'required|exists:users,id',
    ]);

    $order = TravelOrder::create($validated);

    return response()->json([
        'message' => 'Travel order created successfully',
        'data' => $order
    ], 201);
}
    /**
     * Buscar pedido por ID
     */
    public function show($id)
    {
        $order = TravelOrder::findOrFail($id);

        return response()->json($order);
    }

    /**
     * Atualizar status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,canceled'
        ]);

        $order = TravelOrder::findOrFail($id);

        // Regra de negócio
        if ($order->status === 'approved' && $request->status === 'canceled') {
            return response()->json([
                'message' => 'Approved orders cannot be canceled'
            ], 422);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $order
        ]);
    }
}