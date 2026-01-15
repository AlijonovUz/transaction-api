<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Models\User;
use App\Support\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $user->refresh();

        return Response::success(new UserResource($user), 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return Response::error(401, "Invalid credentials", isFriendly: true);
        }

        $user = $request->user();

        if (!$user->is_active) {
            Auth::logout();
            return Response::error(403, "Your account has been blocked", isFriendly: true);
        }

        $token = $user->createToken('api')->plainTextToken;

        return Response::success([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response::success(['message' => "Logged out"]);
    }
}
