<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class AuthKaryawanController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:karyawans',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Karyawan::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 422);
        }
        if (!$token = auth()->guard('karyawan-api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('karyawan-api')->factory()->getTTL() * 60,
            'user' => auth()->guard('karyawan-api')->user()
        ]);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->guard('karyawan-api')->refresh());
    }

    public function profile()
    {
        return response()->json(auth()->guard('karyawan-api')->user());
    }

    public function logout()
    {
        auth()->guard('karyawan-api')->logout();
        return response()->json([
            'message' => 'User successfully signed out'
        ], 201);
    }
}
