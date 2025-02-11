<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID kategori"),
 *     @OA\Property(property="name", type="string", example="Kategori A", description="Nama kategori")
 * )
 */
class Category extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
