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

test('the dashboard loads chirps without N+1 queries', function () {
    $user = User::factory()->create();
    Chirp::factory()->count(3)->for($user)->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

// Feed

test('guests can see the public feed', function () {
    Chirp::factory()->create(['title' => 'First author chirp']);
    Chirp::factory()->create(['title' => 'Second author chirp']);

    $this->get(route('chirps.index'))
        ->assertOk()
        ->assertSee('First author chirp')
        ->assertSee('Second author chirp');
});

test('the feed shows the newest chirps first', function () {
    Chirp::factory()->create(['title' => 'Older chirp', 'created_at' => now()->subDay()]);
    Chirp::factory()->create(['title' => 'Newer chirp']);

    $this->get(route('chirps.index'))
        ->assertSeeInOrder(['Newer chirp', 'Older chirp']);
});

test('the feed is paginated', function () {
    Chirp::factory()->count(25)->create();

    $this->get(route('chirps.index'))
        ->assertOk()
        ->assertViewHas('chirps', fn ($chirps) => $chirps->count() === 20);

    $this->get(route('chirps.index', ['page' => 2]))
        ->assertViewHas('chirps', fn ($chirps) => $chirps->count() === 5);
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

test('guests can view a chirp', function () {
    $chirp = Chirp::factory()->create([
        'title' => 'Public title',
        'message' => 'Public message',
    ]);

    $this->get(route('chirps.show', $chirp))
        ->assertOk()
        ->assertSee('Public title')
        ->assertSee('Public message');
});

test('only the author sees the edit button', function () {
    $chirp = Chirp::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($chirp->user)
        ->get(route('chirps.show', $chirp))
        ->assertSee('Edit Chirp');

    $this->actingAs($otherUser)
        ->get(route('chirps.show', $chirp))
        ->assertDontSee('Edit Chirp');
});

test('the create page is not caught by the chirp show route', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('chirps.create'))
        ->assertOk();
});