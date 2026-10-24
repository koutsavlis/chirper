<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ChirpSeeder extends Seeder
{
    public function run(): void
    {
        // Create a few sample users if they don't exist
        $users = User::query()->count() < 3
            ? collect([
                User::create([
                    'name' => 'Akis Developer',
                    'email' => 'akis@example.com',
                    'password' => bcrypt('password'),
                ]),
                User::create([
                    'name' => 'Bob Builder',
                    'email' => 'bob@example.com',
                    'password' => bcrypt('password'),
                ]),
                User::create([
                    'name' => 'Charlie Coder',
                    'email' => 'charlie@example.com',
                    'password' => bcrypt('password'),
                ]),
            ])
            : User::query()->take(3)->get();

        // Sample chirps
        $chirps = [
            ['title' => 'Hello Laravel', 'message' => 'Just discovered Laravel - where has this been all my life? 🚀'],
            ['title' => 'Today\'s build', 'message' => 'Building something cool with Chirper today!'],
            ['title' => 'Eloquent', 'message' => 'Laravel\'s Eloquent ORM is pure magic ✨'],
            ['title' => 'First deploy', 'message' => 'Deployed my first app with Laravel Cloud. So smooth!'],
            ['title' => 'Blade components', 'message' => 'Who else is loving Blade components?'],
            ['title' => 'Friday deploys', 'message' => 'Friday deploys with Laravel? No problem! 😎'],
        ];

        // Create chirps for random users
        foreach ($chirps as $chirp) {
            $users->random()->chirps()->create([
                ...$chirp,
                'created_at' => now()->subMinutes(rand(5, 1440)),
            ]);
        }
    }
}
