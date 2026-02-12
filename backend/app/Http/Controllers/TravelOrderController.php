<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Notifications\TravelOrderStatusNotification;

use App\Support\ApiResponse;
use App\Support\StatusCode;

class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = TravelOrder::query()
            ->where('user_id', auth()->id())
            ->filters($request->all())
            ->latest()
            ->paginate(10);

        return ApiResponse::success($orders);
    }

    public function store(StoreTravelOrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'requested';

        $order = TravelOrder::create($data);

        return ApiResponse::success($order, 'Pedido criado', StatusCode::CREATED);
    }

    public function show($id)
    {
        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        return ApiResponse::success($order);
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

        return ApiResponse::success($order);
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $order = TravelOrder::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            return ApiResponse::error('Apenas administradores podem alterar status', StatusCode::FORBIDDEN);
        
        }

        if ($order->status === 'approved' && $request->status === 'cancelled') {
            return ApiResponse::error('Pedido já aprovado não pode ser cancelado', StatusCode::UNPROCESSABLE_ENTITY);
        }

        $order->update([
            'status' => $request->status
        ]);

        $order->user->notify(
            new TravelOrderStatusNotification($order)
        );

        return ApiResponse::success($order);
    }

    public function destroy($id)
    {
        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        $order->delete();

        return ApiResponse::success([], 'Pedido removido com sucesso');
    }
}
