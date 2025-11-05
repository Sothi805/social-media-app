<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Save;

class SaveController extends Controller
{
    /**
     * Toggle save/unsave for a post
     */
    public function toggle(Post $post)
    {
        $user = auth()->user();
        $existingSave = \App\Models\Save::where('user_id', $user->id)
                           ->where('post_id', $post->id)
                           ->first();
        $saved = false;
        if ($existingSave) {
            $existingSave->delete();
            $saved = false;
        } else {
            \App\Models\Save::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $saved = true;
        }
        if (request()->expectsJson()) {
            return response()->json(['saved' => $saved, 'saves_count' => $post->saves()->count()]);
        }
        return back();
    }
}
