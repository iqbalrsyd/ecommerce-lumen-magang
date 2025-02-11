<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     description="Model untuk pesanan",
 *     required={"id", "product_id", "user_id", "quantity", "total_price"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID pesanan"),
 *     @OA\Property(property="product_id", type="integer", example=1, description="ID produk"),
 *     @OA\Property(property="user_id", type="integer", example=1, description="ID user"),
 *     @OA\Property(property="quantity", type="integer", example=2, description="Jumlah produk"),
 *     @OA\Property(property="total_price", type="number", format="float", example=100000, description="Total harga pesanan"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z", description="Waktu pembuatan pesanan"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z", description="Waktu terakhir pembaruan pesanan")
 * )
 */
class Order extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity', 'total_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
