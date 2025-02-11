<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'messages' => $validator->errors()
                ], 422);
            }
    
            // Buat kategori baru
            $category = Category::create([
                'name' => $request->input('name'),
            ]);
    
            return response()->json([
                'message' => 'Category created successfully.',
                'category' => $category
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
    
}
