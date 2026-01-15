<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Me\UpdatePasswordRequest;
use App\Http\Requests\Me\UpdateRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Models\User;
use App\Support\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me()
    {
        $user = auth()->user();
        return Response::success(new UserResource($user));
    }

    public function index()
    {
        $users = User::query()->paginate(10);
        return Response::success([
            'results' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total()
            ]
        ]);
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

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return Response::error(422, "The current password is incorrect.");
        }

        if (Hash::check($request->password, $user->password)) {
            return Response::error(422, "The new password must not be the same as the old password.");
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->noContent();
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
