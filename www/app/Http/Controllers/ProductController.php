<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use OpenApi\Annotations as OA;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Get all products.
     *
     * @OA\Get(
     *     path="/product",
     *     summary="Get all products",
     *     tags={"Product"},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar semua produk",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     )
     * )
     */
    
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products, 200);
    }

    /**
     * Get a product by ID.
     *
     * @OA\Get(
     *     path="/product/{id}",
     *     summary="Get product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID dari produk",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail produk",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan"
     *     )
     * )
    */

    public function show($id)
    {
        try {
            // Cek apakah ID valid
            if (!is_numeric($id)) {
                Log::warning("Invalid product ID format: " . $id);
                return response()->json([
                    'error' => 'Invalid product ID format'
                ], 400);
            }
    
            // Mencari produk berdasarkan ID
            $product = Product::find($id);
    
            // Jika produk tidak ditemukan
            if (!$product) {
                Log::error("Product not found", ['product_id' => $id]);
                return response()->json([
                    'error' => 'Product not found'
                ], 404);
            }
    
            return response()->json([
                'message' => 'Product retrieved successfully',
                'product' => $product
            ], 200);
    
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'product_id' => $id
            ]);
            return response()->json([
                'error' => 'Database Error'
            ], 500);
        } catch (\Exception $e) {
            Log::error("Internal Server Error: " . $e->getMessage(), [
                'product_id' => $id
            ]);
            return response()->json([
                'error' => 'Internal Server Error'
            ], 500);
        }
    }
    

    /**
     * Create one or multiple products.
     *
     * @OA\Post(
     *     path="/products",
     *     summary="Create multiple products",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="Array of products to create",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="Product A"),
     *                     @OA\Property(property="description", type="string", example="Description of Product A"),
     *                     @OA\Property(property="price", type="number", format="float", example=100.50)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Products created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation error message")
     *         )
     *     )
     * )
    */
    
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'products' => 'required|array',
                'products.*.name' => 'required|string',
                'products.*.description' => 'required|string',
                'products.*.price' => 'required|numeric',
                'products.*.category_id' => 'required|exists:categories,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'messages' => $validator->errors()
                ], 422);
            }
    
            $products = $request->input('products');
            $insertedProducts = [];
    
            foreach ($products as $product) {
                // Cek apakah kategori ditemukan
                $category = Category::find($product['category_id']);
    
                if (!$category) {
                    Log::error("Category not found for category ID: " . $product['category_id']);
                    return response()->json([
                        'error' => 'Category not found'
                    ], 404);
                }
    
                // Simpan produk
                $insertedProducts[] = Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'category_id' => $category->id,
                ]);
            }
    
            return response()->json([
                'message' => 'Products created successfully',
                'products' => $insertedProducts
            ], 201);
    
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Database Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Database Error'
            ], 500);
        } catch (\Exception $e) {
            Log::error("Internal Server Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error'
            ], 500);
        }
    }
    

    /**
     * Update a product by ID.
     *
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Update a product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="description", type="string", example="Updated Product Description"),
     *             @OA\Property(property="price", type="number", format="float", example=150.75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function update (Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        $product->update($request->all());
        return response()->json($product, 200);
    }

    /**
     * Delete a product by ID.
     *
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Delete a product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function delete ($id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }

    /**
     * Assign a category to a product.
     *
     * @OA\Post(
     *     path="/products/{id}/assign-category",
     *     summary="Assign a category to a product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="category_id",
     *                 type="integer",
     *                 description="ID of the category",
     *                 example=1
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category assigned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Category assigned to product successfully."),
     *             @OA\Property(
     *                 property="product",
     *                 ref="#/components/schemas/Product"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product or category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product or category not found")
     *         )
     *     )
     * )
     */
    public function assignCategory(Request $request, $id)
    {
        // Validasi input
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
        ]);

        // Temukan produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Temukan kategori berdasarkan ID
        $category = Category::findOrFail($request->category_id);

        // Assign kategori ke produk
        $product->category_id = $category->id;
        $product->save();

        // Kembalikan respons
        return response()->json([
            'message' => 'Category assigned to product successfully.',
            'product' => $product,
        ]);
    }

    /**
     * Show products by category ID.
     *
     * @OA\Get(
     *     path="/categories/{categoryId}/products",
     *     summary="Get products by category",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of products in the category",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="category",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Electronics")
     *             ),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function showProducts($categoryId)   
    {
        $category = Category::with('products')->findOrFail($categoryId);
        return response()->json(['category' => $category, 'products' => $category->products], 200);
    }

    public function search(Request $request)
    {
        $query = $request->input('name');

        if (!$query) {
            return ApiResponse::error('Parameter nama produk harus diisi.', 400);
        }

        $products = Product::where('name', 'like', '%' . $query . '%')->get();

        if ($products->isEmpty()) {
            return ApiResponse::success([], 'Produk tidak ditemukan.', 404);
        }

        return ApiResponse::success($products, 'Produk ditemukan.', 200);
    }
}
