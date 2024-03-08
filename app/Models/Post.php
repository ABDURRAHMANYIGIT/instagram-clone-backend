<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\Factory;


class Post extends Model
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return PostFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function likePost(string $postId)
    {
        $post = Post::find($postId);

        auth()->user()->likes()->toggle($post);

        if (auth()->user()->likes()->where('post_id', $post->id)->exists()) {
            return [
                'success' => true,
                'message' => 'User liked the post successfully'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'User unliked the post successfully'
            ];
        }
    }

    protected $fillable = ['description', 'image'];
}
