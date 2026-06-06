<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('customer');
        $token = auth('api')->login($user);
        Cart::create(['user_id' => $user->id]);
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    } 
    
    public function login(LoginRequest $request)
    {
        if(!$token = auth('api')->attempt($request->only('email' , 'password'))){
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        // $user = User::where('email', $request->email)->first();
        
        // if($user){
        //     return back()->withErrors(['email' => 'User not found']);
        // }
        
        return response()->json([
            'user' => auth('api')->user(),
            'token' => $token
        ]);
        
    }


    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'berhasil logout']);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}