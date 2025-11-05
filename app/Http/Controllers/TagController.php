<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Search for tags (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $tags = Tag::search($query)
                   ->popular()
                   ->limit(10)
                   ->get(['id', 'name', 'color']);
        
        return response()->json($tags);
    }

    /**
     * Get or create a tag
     */
    public function findOrCreate($tagName)
    {
        $tag = Tag::where('name', $tagName)->first();
        
        if (!$tag) {
            $tag = Tag::create([
                'name' => $tagName,
                'color' => $this->getRandomColor()
            ]);
        }
        
        return $tag;
    }

    /**
     * Get popular tags
     */
    public function popular()
    {
        $tags = Tag::popular()
                   ->limit(20)
                   ->get();
        
        return response()->json($tags);
    }

    /**
     * Get posts by tag
     */
    public function posts(Tag $tag)
    {
        $posts = $tag->posts()
                    ->with(['user', 'likes', 'comments', 'tags'])
                    ->withCount(['likes', 'comments'])
                    ->latest()
                    ->paginate(10);
        
        return view('tags.show', compact('tag', 'posts'));
    }

    /**
     * Get random color for new tags
     */
    private function getRandomColor()
    {
        $colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', 
            '#8B5CF6', '#F97316', '#06B6D4', '#84CC16',
            '#EC4899', '#6366F1', '#14B8A6', '#F43F5E'
        ];
        
        return $colors[array_rand($colors)];
    }
}
