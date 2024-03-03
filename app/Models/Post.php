<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

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
