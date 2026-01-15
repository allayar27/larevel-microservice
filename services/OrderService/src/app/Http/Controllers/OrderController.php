<?php

namespace App\Http\Controllers;

use App\Jobs\OrderCreated;
use App\Jobs\ProductOrderCreated;
use App\Models\Order;
use App\Models\ProductRead;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get('auth_user_id');
        $role   = $request->get('auth_user_role');

        if ($role === 'admin') {
            $orders = Order::latest()->get();
        }
        // USER видит только свои
        else {
            $orders = Order::where('user_id', $userId)
                ->latest()
                ->get();
        }

        return $this->succes($orders);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $product = ProductRead::where('product_id', '=', $request->product_id)->firstOrFail();

        $order = Order::query()->create([
            'product_id' => $product->product_id,
            'count' => $request->count,
            'user_id'=> $request->auth_user_id,
            'total_price' => (float)$product->price * $request->count
        ]);

        return $this->succes($order, 'Order created', 201);
    }
}
