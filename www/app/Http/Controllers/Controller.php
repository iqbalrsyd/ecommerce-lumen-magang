<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Ecommerce Lumen Docs",
 *     version="v1",
 *     description="Merupakan dokumentasi API ecommerce dengan lumen laravel"
 * )
 */
class Controller extends BaseController
{
    public function getData()
    {
        return response()->json(['data' => 'Hello World']);
    }
}
