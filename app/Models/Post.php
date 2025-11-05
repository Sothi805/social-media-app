<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image_path',
    ];

    /**
     * Get the user that owns the post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes for the post
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the comments for the post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the saves for the post
     */
    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    /**
     * Get the tags for the post
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Get the mentioned users in the post
     */
    public function mentionedUsers()
    {
        return $this->belongsToMany(User::class, 'post_user_mentions', 'post_id', 'user_id')
                    ->withPivot('mentioned_by')
                    ->withTimestamps();
    }

    /**
     * Check if the post is saved by a specific user
     */
    public function isSavedBy(User $user)
    {
        return $this->saves()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if the post is liked by a specific user
     */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the like count
     */
    public function getLikesCountAttribute()
    {
        return $this->likes_count ?? $this->likes()->count();
    }

    /**
     * Get the comments count
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments_count ?? $this->comments()->count();
    }
}
