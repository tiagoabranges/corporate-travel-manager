<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,canceled'
    ]);

    $order = TravelOrder::findOrFail($id);

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

public function show($id)
{
    $order = TravelOrder::findOrFail($id);

    return response()->json($order);
}
}