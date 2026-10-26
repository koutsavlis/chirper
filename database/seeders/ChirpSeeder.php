<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ChirpSeeder extends Seeder
{
    /**
     * Seed demo data. Safe to run more than once.
     */
    public function run(): void
    {
        // The account shown in the README for visitors to try the app
        $demo = User::query()->firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo User', 'password' => bcrypt('password')],
        );

        // Other users, so the public feed looks like a real community
        $others = collect([
            ['name' => 'Bob Builder', 'email' => 'bob@example.com'],
            ['name' => 'Charlie Coder', 'email' => 'charlie@example.com'],
        ])->map(fn (array $user) => User::query()->firstOrCreate(
            ['email' => $user['email']],
            ['name' => $user['name'], 'password' => bcrypt('password')],
        ));

        $demoChirps = [
            ['title' => 'Welcome to Chirper', 'message' => 'This is the demo account. Try editing or deleting this chirp!'],
            ['title' => 'My dashboard', 'message' => 'Only I can see the edit and delete buttons on my own chirps.'],
        ];

        $communityChirps = [
            ['title' => 'Hello Laravel', 'message' => 'Just discovered Laravel - where has this been all my life? 🚀'],
            ['title' => 'Today\'s build', 'message' => 'Building something cool with Chirper today!'],
            ['title' => 'Eloquent', 'message' => 'Laravel\'s Eloquent ORM is pure magic ✨'],
            ['title' => 'First deploy', 'message' => 'Deployed my first app with Laravel Cloud. So smooth!'],
            ['title' => 'Blade components', 'message' => 'Who else is loving Blade components?'],
            ['title' => 'Friday deploys', 'message' => 'Friday deploys with Laravel? No problem! 😎'],
        ];

        foreach ($demoChirps as $chirp) {
            $demo->chirps()->firstOrCreate(['title' => $chirp['title']], $chirp);
        }

        // Spread community chirps evenly across the other users
        foreach ($communityChirps as $index => $chirp) {
            $others[$index % $others->count()]
                ->chirps()
                ->firstOrCreate(['title' => $chirp['title']], $chirp);
        }
    }
}