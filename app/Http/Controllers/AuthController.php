<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }
        $input = $request->all();
              $user = User::create([
                      'name' => $input['name'],
                           'email' => $input['email'],
                           'password' => Hash::make($input['password']),
               ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                      'access_token' => $token,
                           'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        //dd($request->all());
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
            'message' => 'Invalid login details'
                       ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                   'access_token' => $token,
                   'token_type' => 'Bearer',
        ]);
    }
    public function me(Request $request)
    {
        dd($request->user());
        return $request->user();
    }
}
