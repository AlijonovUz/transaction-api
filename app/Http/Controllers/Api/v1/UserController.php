<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Me\UpdateRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Models\User;
use App\Support\Response;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me()
    {
        $user = auth()->user();
        return Response::success(new UserResource($user));
    }

    public function show(User $user)
    {
        return Response::success(new UserResource($user));
    }

    public function updateMe(UpdateRequest $request)
    {
        $user = auth()->user();
        $user->update($request->validated());
        return Response::success(new UserResource($user));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        return Response::success(new UserResource($user));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
