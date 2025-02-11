<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Helpers\ApiResponse;

class OrderController extends Controller
{
    /**
     * Membuat pesanan baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->input('product_id'));

        $totalPrice = $product->price * $request->input('quantity');

        $order = Order::create([
            'product_id' => $request->input('product_id'),
            'user_id' => $request->input('user_id'),
            'quantity' => $request->input('quantity'),
            'total_price' => $totalPrice,
        ]);

        return ApiResponse::success($order, 'Pesanan berhasil dibuat.', 201);
    }
}
