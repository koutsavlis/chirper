<?php

namespace App\Policies;

use App\Models\Chirp;
use App\Models\User;

class ChirpPolicy
{
    /**
     * Only the author can view a chirp.
     */
    public function view(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }

    /**
     * Only the author can update a chirp.
     */
    public function update(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }

    /**
     * Only the author can delete a chirp.
     */
    public function delete(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }
}