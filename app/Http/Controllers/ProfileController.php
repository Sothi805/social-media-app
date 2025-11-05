<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function index(){
        $user = auth()->user();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $posts = $user->posts()->withCount(['likes', 'comments'])->latest()->get();
        $postsCount = $posts->count();
        
        // Get saved posts with their details
        $savedPosts = $user->savedPosts()
                          ->with(['user', 'likes', 'comments'])
                          ->withCount(['likes', 'comments'])
                          ->latest('saves.created_at')
                          ->get();
        
        return view("profile.index", compact('user', 'followersCount', 'followingCount', 'posts', 'postsCount', 'savedPosts'));
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle profile picture upload
        if ($request->hasFile('profile_photo_path')) {
            // Delete old profile picture if it exists
            if ($user->profile_photo_path && file_exists(public_path('storage/' . $user->profile_photo_path))) {
                unlink(public_path('storage/' . $user->profile_photo_path));
            }
            
            // Store new profile picture with custom naming
            $file = $request->file('profile_photo_path');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->username . '-' . time() . '.' . $extension;
            $path = $file->storeAs('profile-photos', $filename, 'public');
            $validated['profile_photo_path'] = $path;
        } else {
            // Remove profile_photo_path from validated data if no file was uploaded
            unset($validated['profile_photo_path']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.index')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
