<?php

namespace App\Policies;

use App\Models\{User, Delegation};

class DelegationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Delegation $delegation): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh', 'paie']);
    }

    public function update(User $user, Delegation $delegation): bool
    {
        return in_array($user->role, ['admin', 'rh', 'paie']);
    }

    public function delete(User $user, Delegation $delegation): bool
    {
        return $user->role === 'admin';
    }
}
