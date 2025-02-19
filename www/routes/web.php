<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->group(['prefix' => 'api/v1'], function () use ($router) {
    /**
     * @OA\Get(
     *     path="/api/v1/product/{id}",
     *     summary="Get product by ID",
     *     description="Retrieve a product by its ID",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to get",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */

    $router->post('signup', 'AuthController@signup');
    $router->post('login', 'AuthController@login');

    $router->get('profile', ['middleware' => 'auth', function () {
        return response()->json(['message' => 'You are authenticated!']);
    }]);
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    /**
     * @OA\Get(
     *     path="/api/v1/product/{id}",
     *     summary="Get product by ID",
     *     description="Retrieve a product by its ID",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to get",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    $router->get('product', ['middleware' => 'auth', 'uses' => 'ProductController@index']);
    $router->get('product/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@show']);
    $router->post('product', ['middleware' => 'auth', 'uses' => 'ProductController@store']);
    $router->put('product/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@update']);
    $router->delete('product/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@delete']); 

    $router->get('/product/search/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@search']);

    $router->post('product/{id}/assign-category', 'ProductController@assignCategory');
    $router->get('category/{id}/product', 'CategoryController@showProducts');
    $router->post('category', 'CategoryController@store');

    $router->post('order', ['middleware' => 'auth', 'uses' => 'OrderController@create']);
    
});
