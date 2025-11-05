<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Return post and all comments as JSON for modal
     */
    public function commentsJson(Post $post)
    {
        $post->load('user');
        $comments = $post->comments()->with('user')->latest()->get();
        return response()->json([
            'post' => [
                'id' => $post->id,
                'user_name' => $post->user->username,
                'user_avatar' => $post->user->profile_photo_path
                    ? asset('storage/' . $post->user->profile_photo_path)
                    : asset('images/pfp/default-avatar.png'),
                'created_at' => $post->created_at->diffForHumans(),
                'caption' => $post->content,
                'image_url' => $post->image_path ? asset('storage/' . $post->image_path) : null,
            ],
            'comments' => $comments->map(function($c) {
                return [
                    'id' => $c->id,
                    'user_name' => $c->user->username,
                    'body' => $c->content,
                    'created_at' => $c->created_at->diffForHumans(),
                ];
            }),
        ]);
    }
    /**
     * Display a listing of posts for the home feed
     */
    public function index()
    {
        // Get posts from users that the current user follows, plus their own posts
        $followingIds = Auth::user()->following()->pluck('users.id');
        $followingIds->push(Auth::id()); // Include current user's posts
        
        $posts = Post::whereIn('user_id', $followingIds)
                    ->with([
                        'user', 
                        'likes', 
                        'comments' => function($query) {
                            $query->with('user')->latest()->take(3);
                        },
                        'tags',
                        'mentionedUsers'
                    ])
                    ->withCount(['likes', 'comments'])
                    ->latest()
                    ->paginate(10);
                    
        return view('home', compact('posts'));
    }

    /**
     * Show the form for creating a new post
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tags' => 'nullable|string',
            'mentions' => 'nullable|string'
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->input('content');

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = Auth::user()->username . '-post-' . time() . '.' . $extension;
            $path = $file->storeAs('posts', $filename, 'public');
            $post->image_path = $path;
        }

        $post->save();

        // Handle tags
        if ($request->filled('tags')) {
            $this->syncTags($post, $request->input('tags'));
        }

        // Handle mentions
        if ($request->filled('mentions')) {
            $this->syncMentions($post, $request->input('mentions'));
        }

        return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        $post->load(['user', 'likes', 'comments.user']);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post
     */
    public function edit(Post $post)
    {
        // Check if the user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        // Check if the user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tags' => 'nullable|string',
            'mentions' => 'nullable|string'
        ]);

        $post->content = $request->input('content');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
                Storage::disk('public')->delete($post->image_path);
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = Auth::user()->username . '-post-' . time() . '.' . $extension;
            $path = $file->storeAs('posts', $filename, 'public');
            $post->image_path = $path;
        }

        $post->save();

        // Handle tags
        if ($request->has('tags')) {
            $this->syncTags($post, $request->input('tags'));
        }

        // Handle mentions
        if ($request->has('mentions')) {
            $this->syncMentions($post, $request->input('mentions'));
        }

        return redirect()->route('profile.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        // Check if the user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete the image if it exists
        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return back()->with('success', 'Post deleted successfully!');
    }

    /**
     * Like or unlike a post
     */
    public function like(Post $post)
    {
        $user = auth()->user();
        $liked = false;
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            // Unlike
            $post->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            // Like
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }
        if (request()->expectsJson()) {
            return response()->json(['liked' => $liked, 'likes_count' => $post->likes()->count()]);
        }
        return back();
    }

    /**
     * Add a comment to a post
     */
    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('comment'),
        ]);
        return back();
    }

    /**
     * Sync tags for a post
     */
    private function syncTags(Post $post, $tagsString)
    {
        if (empty($tagsString)) {
            $post->tags()->detach();
            return;
        }

        // Parse tags from string (comma-separated or hashtag format)
        $tagNames = collect(explode(',', $tagsString))
            ->map(function ($tag) {
                return trim(str_replace('#', '', $tag));
            })
            ->filter()
            ->unique();

        $tagIds = [];
        foreach ($tagNames as $tagName) {
            if (!empty($tagName)) {
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['color' => $this->getRandomTagColor()]
                );
                $tagIds[] = $tag->id;
            }
        }

        // Update usage counts
        $oldTagIds = $post->tags()->pluck('tags.id')->toArray();
        $newTagIds = $tagIds;

        // Decrement usage for removed tags
        foreach (array_diff($oldTagIds, $newTagIds) as $tagId) {
            Tag::find($tagId)?->decrementUsage();
        }

        // Increment usage for added tags
        foreach (array_diff($newTagIds, $oldTagIds) as $tagId) {
            Tag::find($tagId)?->incrementUsage();
        }

        // Sync the tags
        $post->tags()->sync($tagIds);
    }

    /**
     * Get random color for new tags
     */
    private function getRandomTagColor()
    {
        $colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', 
            '#8B5CF6', '#F97316', '#06B6D4', '#84CC16',
            '#EC4899', '#6366F1', '#14B8A6', '#F43F5E'
        ];
        
        return $colors[array_rand($colors)];
    }

    /**
     * Sync user mentions for a post
     */
    private function syncMentions(Post $post, $mentionsString)
    {
        if (empty($mentionsString)) {
            $post->mentionedUsers()->detach();
            return;
        }

        // Parse mentions - find @username patterns
        preg_match_all('/@([a-zA-Z0-9_]+)/', $mentionsString, $matches);
        $usernames = $matches[1];

        if (empty($usernames)) {
            $post->mentionedUsers()->detach();
            return;
        }

        // Find users by username
        $users = User::whereIn('username', $usernames)->get();
        $mentionData = [];

        foreach ($users as $user) {
            $mentionData[$user->id] = ['mentioned_by' => Auth::id()];
        }

        // Sync the mentions
        $post->mentionedUsers()->sync($mentionData);

        // TODO: Send notifications to mentioned users
    }
}
