<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $articles = Article::with('user')
        ->withCount('comments')
        ->withExists([
            'comments as recent_comments_exists' => function ($query) {
            $query->where('created_at', '>', Carbon::now()->subDay());
        }])
        ->when(Auth::check(), function ($query) {
            $query->whereHas('user', function(Builder $query) {
                $query->whereIn('id', Auth::user()->followings->pluck('id')->push(Auth::id()));
            });
        })
        ->latest()
        ->paginate();

        return view('articles/index', 
        [
            'articles' => $articles
        ]);
    }
}
