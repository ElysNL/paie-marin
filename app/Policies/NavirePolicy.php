<?php

namespace App\Policies;

use App\Models\{User, Navire};

class NavirePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Navire $navire): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function update(User $user, Navire $navire): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function delete(User $user, Navire $navire): bool
    {
        return $user->role === 'admin';
    }
}
