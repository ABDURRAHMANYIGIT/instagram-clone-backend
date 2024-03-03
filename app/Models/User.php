<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set a default value for profile_photo
        $this->attributes['profile_photo'] = 'default.jpg';
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return asset('storage/profile_images/' . $this->profile_photo);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    public function getFollowers()
    {
        return $this->followers()->get();
    }

    public function getFollowings()
    {
        return $this->followings()->get();
    }

    public function getPosts(){
        return $this->posts()->get();
    }

    public function toggleUserFollowing(string $id){
        $userToToggle = User::find($id);
    
        if (!$userToToggle) {
            return [
                'success' => false,
                'message' => 'User does not exist!'
            ];
        }
    
        if (auth()->id() == $userToToggle->id) {
            return [
                'success' => false,
                'message' => 'Cannot follow yourself'
            ];
        }
    
        $userFollowings = $this->followings();

        $userFollowings->toggle($userToToggle);

        if ( $userFollowings->where('user_id', $userToToggle->id)->exists()) {
            return [
                'success' => true,
                'message' => 'User followed successfully'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'User unfollowed successfully'
            ];
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
