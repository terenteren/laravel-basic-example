<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Models\Article;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::controller(ARticleController::class)->group(function () {
//     Route::get('/articles/create', 'create')->name('articles.create');
//     Route::post('/articles', 'store')->name('articles.store');
//     Route::get('articles', 'index')->name('articles.index');
//     Route::get('articles/{article}', 'show')->name('articles.show');
//     Route::get('articles/{article}/edit', 'edit')->name('articles.edit');
//     Route::patch('articles/{article}', 'update')->name('articles.update');
//     Route::delete('articles/{article}', 'destroy')->name('articles.delete');
// });

Route::get('/', HomeController::class)->name('home');

Route::resource('articles', ARticleController::class);

Route::resource('comments', CommentController::class);

Route::get('profile/{user:username}', [ProfileController::class, 'show'])
->name('profile')
->where('user', '^[A-Za-z0-9-]+$');

Route::post('follow/{user}', [FollowController::class, 'store'])->name('follow');
Route::delete('follow/{user}', [FollowController::class, 'destory'])->name('unfollow');

