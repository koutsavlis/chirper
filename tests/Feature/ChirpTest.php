<?php

use App\Models\Chirp;
use App\Models\User;

// Dashboard

test('the dashboard shows only the user\'s own chirps', function () {
    $user = User::factory()->create();
    Chirp::factory()->for($user)->create(['title' => 'My first chirp']);
    Chirp::factory()->create(['title' => 'A stranger chirp']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('My first chirp')
        ->assertDontSee('A stranger chirp');
});

// Create

test('a user can create a chirp', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('chirps.store'), [
            'title' => 'Hello',
            'message' => 'My first chirp',
        ])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('chirps', [
        'user_id' => $user->id,
        'title' => 'Hello',
        'message' => 'My first chirp',
    ]);
});

test('the success message is shown after creating a chirp', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->followingRedirects()
        ->post(route('chirps.store'), [
            'title' => 'Hello',
            'message' => 'My first chirp',
        ])
        ->assertSee('Chirp created successfully!');
});

// Validation

test('a chirp requires a title', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('chirps.store'), [
            'title' => '',
            'message' => 'No title here',
        ])
        ->assertSessionHasErrors('title');

    $this->assertDatabaseCount('chirps', 0);
});

test('a chirp message cannot exceed 255 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('chirps.store'), [
            'title' => 'Too long',
            'message' => str_repeat('a', 256),
        ])
        ->assertSessionHasErrors('message');

    $this->assertDatabaseCount('chirps', 0);
});

// Update

test('a user can update their own chirp', function () {
    $chirp = Chirp::factory()->create();

    $this->actingAs($chirp->user)
        ->put(route('chirps.update', $chirp), [
            'title' => 'Updated',
            'message' => 'Updated message',
        ])
        ->assertRedirect(route('dashboard'));

    expect($chirp->fresh())
        ->title->toBe('Updated')
        ->message->toBe('Updated message');
});

test('a user cannot update someone else\'s chirp', function () {
    $chirp = Chirp::factory()->create(['title' => 'Original']);
    $intruder = User::factory()->create();

    $this->actingAs($intruder)
        ->put(route('chirps.update', $chirp), [
            'title' => 'Hacked',
            'message' => 'Hacked',
        ])
        ->assertForbidden();

    expect($chirp->fresh()->title)->toBe('Original');
});

// Delete

test('a user can delete their own chirp', function () {
    $chirp = Chirp::factory()->create();

    $this->actingAs($chirp->user)
        ->delete(route('chirps.destroy', $chirp))
        ->assertRedirect(route('dashboard'));

    $this->assertModelMissing($chirp);
});

test('a user cannot delete someone else\'s chirp', function () {
    $chirp = Chirp::factory()->create();
    $intruder = User::factory()->create();

    $this->actingAs($intruder)
        ->delete(route('chirps.destroy', $chirp))
        ->assertForbidden();

    $this->assertModelExists($chirp);
});

// View

test('a user cannot view someone else\'s chirp', function () {
    $chirp = Chirp::factory()->create();
    $intruder = User::factory()->create();

    $this->actingAs($intruder)
        ->get(route('chirps.show', $chirp))
        ->assertForbidden();
});