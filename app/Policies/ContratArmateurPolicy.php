<?php

namespace App\Policies;

use App\Models\{User, ContratArmateur};

class ContratArmateurPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ContratArmateur $contrat): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function update(User $user, ContratArmateur $contrat): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function delete(User $user, ContratArmateur $contrat): bool
    {
        return $user->role === 'admin';
    }
}
