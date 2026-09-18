<?php

namespace App\Policies;

use App\Models\User;

class ReferentielPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function update(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function delete(User $user): bool
    {
        return $user->role === 'admin';
    }
}
