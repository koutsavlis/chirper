<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the authenticated user's own chirps.
     */
    public function __invoke(Request $request)
    {
        $chirps = $request->user()
            ->chirps()
            ->with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('dashboard', ['chirps' => $chirps]);
    }
}