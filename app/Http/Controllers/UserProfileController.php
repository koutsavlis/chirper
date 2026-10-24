<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Show a user's public profile with their chirps.
     */
    public function __invoke(User $user)
    {
        $chirps = $user->chirps()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('users.show', compact('user', 'chirps'));
    }
}