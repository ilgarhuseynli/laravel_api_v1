<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // register a new user method
    public function register(RegisterRequest $request) {

        $data = $request->validated();

        $_user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $_user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return Res::success(['token' => $token, 'user' => $_user])->withCookie($cookie);

    }

    public function login(LoginRequest $request): JsonResponse
    {
        $password = str_replace(' ','+', $request['password']);

        if (Auth::attempt(['email' => $request['email'], 'password' => $password], $request['remember'])) {

            $_user = Auth::user();

            $token = $_user->createToken('auth_token')->plainTextToken;

            $cookie = cookie('token', $token, 60 * 24); // 1 day

            return Res::success(['token' => $token, 'user' => $_user])->withCookie($cookie);

        } else {
            return Res::error('Error','Invalid credentials',422);
        }
    }


    // logout a user method
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return Res::success([])->withCookie($cookie);
    }

    // get the authenticated user method
    public function user(Request $request) {
        return new UserResource($request->user());
    }
}
