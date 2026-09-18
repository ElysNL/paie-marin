<?php

namespace App\Policies;

use App\Models\{User, AffectationMarin};

class AffectationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AffectationMarin $affectation): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh', 'paie']);
    }

    public function update(User $user, AffectationMarin $affectation): bool
    {
        return in_array($user->role, ['admin', 'rh', 'paie']);
    }

    public function delete(User $user, AffectationMarin $affectation): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }
}
