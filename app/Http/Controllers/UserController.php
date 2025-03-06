<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userService->updateProfile(Auth::id(), $request->validated());

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    public function getMyProfile()
    {
        $user = $this->userService->getUserProfile(Auth::id());

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    public function getOtherUserProfile(int $userId)
    {
        $user = $this->userService->getUserProfile($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    public function searchUserByName(string $name)
    {
        $users = $this->userService->searchUserByName($name);

        return UserResource::collection($users);
    }

    public function followUser(int $userId)
    {


        if (Auth::id() === $userId) {
            return response()->json(['message' => 'Cannot follow yourself'], 400);
        }

        Auth::following()->attach($userId);

        return response()->json(['message' => 'Followed user']);
    }

    public function unfollowUser(int $userId)
    {
        Auth::following()->detach($userId);

        return response()->json(['message' => 'Unfollowed user']);
    }

    public function getFollowers(int $userId)
    {
        $user = $this->userService->getUserProfile($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return UserResource::collection($user->followers);
    }

    public function getFollowing(int $userId)
    {
         $user = $this->userService->getUserProfile($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return UserResource::collection($user->following);
    }
}
