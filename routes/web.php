<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Health & Wellness Community
|--------------------------------------------------------------------------
*/

// ─── Public Home ─────────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('posts.index'))->name('home');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Posts (publicly readable, auth required to write) ───────────────────────
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Post CRUD
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comments
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Likes
    Route::post('/posts/{postId}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // Bookmarks
    Route::post('/posts/{postId}/bookmark', [BookmarkController::class, 'toggle'])->name('posts.bookmark');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Profile (public — anyone can view a user's profile)
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

// ─── Wellness Resources (public) ─────────────────────────────────────────────
Route::prefix('resources')->name('resources.')->group(function () {
    Route::get('/', [ResourceController::class, 'index'])->name('index');
    Route::get('/health-tips', [ResourceController::class, 'healthTips'])->name('health-tips');
    Route::get('/meditation', [ResourceController::class, 'meditation'])->name('meditation');
    Route::get('/fitness', [ResourceController::class, 'fitness'])->name('fitness');
    Route::get('/nutrition', [ResourceController::class, 'nutrition'])->name('nutrition');
});
