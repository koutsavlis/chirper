<?php

use App\Models\Chirp;
use App\Models\User;
use Database\Seeders\ChirpSeeder;

test('the demo seeder can run more than once without duplicates', function () {
    $this->seed(ChirpSeeder::class);
    $this->seed(ChirpSeeder::class);

    expect(User::query()->count())->toBe(3)
        ->and(Chirp::query()->count())->toBe(8);
});

test('the demo account from the README can log in', function () {
    $this->seed(ChirpSeeder::class);

    $this->post('/login', [
        'email' => 'demo@example.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
});