<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        Auth::user()->followings()->attach($user->id);

        return back();
    }

    public function destory(User $user): RedirectResponse
    {
        Auth::user()->followings()->detach($user->id);

        return back();
    }

}
