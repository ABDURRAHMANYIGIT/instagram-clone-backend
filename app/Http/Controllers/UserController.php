<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function toggleFollowUser(string $id)
    {
        $result = auth()->user()->toggleUserFollowing($id);

        if ($result['success']) {
            return response(['result' => true, 'message' => $result['message']], 200);
        } else {
            return response(['result' => false, 'message' => $result['message']], 400);
        }
    }
    

    public function getMyFollowings()
    {
        return response()->json(['data' => auth()->user()->getFollowings()]);
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
        return response()->json(['data' =>  auth()->user()->getFollowers()]);
    }
}
