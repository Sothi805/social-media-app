<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('auth.login');
})->name('welcome');

Route::get('/home', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    // JSON for post + all comments (for modal)
    Route::get('/posts/{post}/comments', [PostController::class, 'commentsJson'])->name('posts.comments.json');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Post routes
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Search and follow routes
    Route::get('/search', [FollowController::class, 'search'])->name('search');
    Route::get('/users/search', [FollowController::class, 'searchUsers'])->name('users.search');
    Route::get('/user/{user}', [FollowController::class, 'profile'])->name('profile.show');
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    
    // Like and comment routes
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/save', [PostController::class, 'save'])->name('posts.save');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Save routes
    Route::post('/posts/{post}/save', [SaveController::class, 'toggle'])->name('posts.save.toggle');
    
    // Tag routes
    Route::get('/tags/search', [App\Http\Controllers\TagController::class, 'search'])->name('tags.search');
    Route::get('/tags/popular', [App\Http\Controllers\TagController::class, 'popular'])->name('tags.popular');
    Route::get('/tags/{tag}/posts', [App\Http\Controllers\TagController::class, 'posts'])->name('tags.posts');
});

require __DIR__.'/auth.php';
