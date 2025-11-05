<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Search for users
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $users = collect();
        
        if ($query) {
            $users = User::where(function($q) use ($query) {
                $q->where('username', 'LIKE', '%' . $query . '%')
                  ->orWhere('first_name', 'LIKE', '%' . $query . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $query . '%');
            })
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->limit(10)
            ->get();
        }
        
        return view('search.index', compact('users', 'query'));
    }

    /**
     * Search users for mentions (AJAX)
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $users = User::where(function($q) use ($query) {
            $q->where('username', 'LIKE', '%' . $query . '%')
              ->orWhere('first_name', 'LIKE', '%' . $query . '%')
              ->orWhere('last_name', 'LIKE', '%' . $query . '%');
        })
        ->where('id', '!=', Auth::id()) // Exclude current user
        ->limit(10)
        ->get(['id', 'username', 'first_name', 'last_name', 'profile_photo_path']);
        
        return response()->json($users);
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        Auth::user()->follow($user);
        
        return back()->with('success', 'You are now following ' . $user->username);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        Auth::user()->unfollow($user);
        
        return back()->with('success', 'You unfollowed ' . $user->username);
    }

    /**
     * Show a user's profile
     */
    public function profile(User $user)
    {
    $isFollowing = Auth::user()->isFollowing($user);
    $followersCount = $user->followers()->count();
    $followingCount = $user->following()->count();
    $posts = $user->posts()->withCount(['likes', 'comments'])->latest()->get();
    $postsCount = $posts->count();
    return view('profile.show', compact('user', 'isFollowing', 'followersCount', 'followingCount', 'posts', 'postsCount'));
    }
}
