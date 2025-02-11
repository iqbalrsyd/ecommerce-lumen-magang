<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Model untuk produk",
 *     required={"id", "name", "price"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID produk"),
 *     @OA\Property(property="name", type="string", example="Produk A", description="Nama produk"),
 *     @OA\Property(property="description", type="string", example="Deskripsi produk A", description="Deskripsi produk"),
 *     @OA\Property(property="price", type="number", format="float", example=50000, description="Harga produk"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z", description="Waktu pembuatan produk"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z", description="Waktu terakhir pembaruan produk")
 * )
 */


class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'created_at', 'updated_at', 'category_id'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}