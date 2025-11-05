<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email',
        'password',
        'bio',
        'occupation',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Users that this user is following
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Users that are following this user
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Check if this user is following another user
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        if (!$this->isFollowing($user) && $this->id !== $user->id) {
            $this->following()->attach($user->id);
        }
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        $this->following()->detach($user->id);
    }

    /**
     * Get the posts for the user
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the saved posts for the user
     */
    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    /**
     * Get the saved posts with post details
     */
    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saves', 'user_id', 'post_id')
                    ->withTimestamps();
    }

    /**
     * Check if user has saved a post
     */
    public function hasSaved(Post $post)
    {
        return $this->saves()->where('post_id', $post->id)->exists();
    }
}
