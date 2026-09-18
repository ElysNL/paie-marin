<?php

namespace App\Policies;

use App\Models\{User, Avance};

class AvancePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Avance $avance): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh', 'paie']);
    }

    public function update(User $user, Avance $avance): bool
    {
        return in_array($user->role, ['admin', 'rh', 'paie']);
    }

    public function delete(User $user, Avance $avance): bool
    {
        return $user->role === 'admin';
    }
}
