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
    /**
     * @OA\Tag(
     *   name="TravelOrders",
     *   description="Gerenciamento de pedidos de viagem"
     * )
     */

    /**
     * @OA\Get(
     *   path="/travel-orders",
     *   summary="Listar pedidos de viagem",
     *   tags={"TravelOrders"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Lista de pedidos")
     * )
     */
    public function index(Request $request)
    {

        $query = TravelOrder::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $orders = $query
            ->with('user:id,name')
            ->filters($request->all())
            ->latest()
            ->paginate(10);


        return ApiResponse::success($orders);
    }

    /**
     * @OA\Post(
     *   path="/travel-orders",
     *   summary="Criar pedido de viagem",
     *   tags={"TravelOrders"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"requester_name","destination","departure_date","return_date"},
     *       @OA\Property(property="requester_name", type="string", example="João Silva"),
     *       @OA\Property(property="destination", type="string", example="Nova York"),
     *       @OA\Property(property="departure_date", type="string", format="date", example="2025-07-10"),
     *       @OA\Property(property="return_date", type="string", format="date", example="2025-07-20")
     *     )
     *   ),
     *
     *   @OA\Response(response=201, description="Pedido criado")
     * )
     */

    public function store(StoreTravelOrderRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'requested';

        $order = TravelOrder::create($data);

        return ApiResponse::success($order, 'Pedido criado', StatusCode::CREATED);
    }

    /**
     * @OA\Get(
     *   path="/travel-orders/{id}",
     *   summary="Mostrar pedido",
     *   tags={"TravelOrders"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Detalhes do pedido"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {

        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        return ApiResponse::success($order);
    }

    /**
     * @OA\Put(
     *   path="/travel-orders/{id}",
     *   summary="Atualizar pedido",
     *   tags={"TravelOrders"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="requester_name", type="string", example="João Silva"),
     *       @OA\Property(property="destination", type="string", example="Paris"),
     *       @OA\Property(property="departure_date", type="string", format="date", example="2025-07-10"),
     *       @OA\Property(property="return_date", type="string", format="date", example="2025-07-20")
     *     )
     *   ),
     *
     *   @OA\Response(response=200, description="Pedido atualizado")
     * )
     */

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
    /**
     * @OA\Patch(
     *   path="/travel-orders/{id}/status",
     *   summary="Atualizar status do pedido",
     *   tags={"TravelOrders"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"status"},
     *       @OA\Property(
     *         property="status",
     *         type="string",
     *         example="approved",
     *         enum={"approved","cancelled"}
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(response=200, description="Status atualizado"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */

    public function updateStatus(UpdateStatusRequest $request, $id)
    {


        $order = TravelOrder::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            return ApiResponse::error('Apenas administradores podem alterar status', StatusCode::FORBIDDEN);
        }

        $current = $order->status;
        $new = $request->status;

        if ($current !== 'requested') {
            return ApiResponse::error(
                'Apenas pedidos com status "requested" podem ser alterados.',
                StatusCode::UNPROCESSABLE_ENTITY
            );
        }

        if (!in_array($new, ['approved', 'cancelled'])) {
            return ApiResponse::error(
                'Status inválido.',
                StatusCode::UNPROCESSABLE_ENTITY
            );
        }


        $order->update([
            'status' => $request->status
        ]);

        if ($order->user) {
            $order->user->notify(
                new TravelOrderStatusNotification($order)
            );
        }


        return ApiResponse::success($order);
    }

    /**
     * @OA\Delete(
     *   path="/travel-orders/{id}",
     *   summary="Remover pedido",
     *   tags={"TravelOrders"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Pedido removido")
     * )
     */
    public function destroy($id)
    {

        $order = TravelOrder::where('user_id', auth()->id())
            ->findOrFail($id);

        $order->delete();

        return ApiResponse::success([], 'Pedido removido com sucesso');
    }
}
