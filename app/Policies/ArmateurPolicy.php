<?php

namespace App\Policies;

use App\Models\{User, Armateur};

class ArmateurPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Armateur $armateur): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function update(User $user, Armateur $armateur): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function delete(User $user, Armateur $armateur): bool
    {
        return $user->role === 'admin';
    }
}
