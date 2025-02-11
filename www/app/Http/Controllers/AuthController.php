<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    
        $hashedPassword = password_hash($request->password, PASSWORD_BCRYPT, ['cost' => 12]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword, 
        ]);
    
        return response()->json(['user' => $user], 201);
    }    

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $token = $user->createToken('Personal Access Token')->accessToken;
    
        return response()->json(['token' => $token], 200);
    }
    
}