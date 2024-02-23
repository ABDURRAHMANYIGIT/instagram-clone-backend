<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function followUser(string $id)
    {
        $userToFollow = User::find($id);

        if (!$userToFollow) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (auth()->id() == $userToFollow->id) {
            return response()->json(['error' => 'Cannot follow yourself'], 400);
        }

        if (auth()->user()->followings()->where('follower_id', $userToFollow->id)->exists()) {
            return response()->json(['error' => 'User is already being followed'], 400);
        }

        auth()->user()->followings()->attach($userToFollow);

        return response()->json(['message' => 'User followed successfully']);
    }

    public function getMyFollowings()
    {
        $followings = auth()->user()->followings()->get();

        return response()->json(['data' => $followings]);
    }

    public function getLikedPosts()
    {
        $likedPosts = auth()->user()->likes()->paginate(15);

        return PostResource::collection($likedPosts);
    }

    public function getLikedPostIds()
    {
        $likedPostIds = auth()->user()->likes()->pluck('post_id')->toArray();

        return response()->json(
            [
                'data' => $likedPostIds
            ],
            200
        );
    }

    public function getMyFollowers()
    {
        $followers = auth()->user()->followers()->get();

        return response()->json(['data' => $followers]);
    }
}
