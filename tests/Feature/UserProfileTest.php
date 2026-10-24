<?php

use App\Models\Chirp;
use App\Models\User;

test('guests can view a user profile with their chirps', function () {
    $user = User::factory()->create(['name' => 'Alice']);
    Chirp::factory()->for($user)->create(['title' => 'Alice chirp']);
    Chirp::factory()->create(['title' => 'Someone else chirp']);

    $this->get(route('users.show', $user))
        ->assertOk()
        ->assertSee('Alice')
        ->assertSee('Alice chirp')
        ->assertDontSee('Someone else chirp');
});

test('the profile shows the number of chirps', function () {
    $user = User::factory()->create();
    Chirp::factory()->count(3)->for($user)->create();

    $this->get(route('users.show', $user))
        ->assertSee('3 chirps');
});

test('a missing user returns 404', function () {
    $this->get('/users/999')
        ->assertNotFound();
});