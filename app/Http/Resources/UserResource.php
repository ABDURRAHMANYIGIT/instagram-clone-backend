<?php

namespace App\Http\Resources;
use App\Http\Resources\PostResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_photo' => $this->getProfilePhotoUrlAttribute(),
            'followers' => $this->mapUserCollection($this->getFollowers()),
            'followings' => $this->mapUserCollection($this->getFollowings()),
            'posts' => $this->mapUserPosts($this),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    protected function mapUserCollection($users)
    {
        return $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile_photo' => $user->getProfilePhotoUrlAttribute(),
            ];
        });
    }

    protected function mapUserPosts($user)
    {
        return $user->posts->map(function ($post) {
            return [
                'id' => $post->id,
                'description' => $post->description,
                'image' => asset('storage/post_images/' . basename($post->image)),
            ];
        });
    }
}
