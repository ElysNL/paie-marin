<?php

namespace App\Policies;

use App\Models\{User, Employe};

class EmployePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Employe $employe): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function update(User $user, Employe $employe): bool
    {
        return in_array($user->role, ['admin', 'rh']);
    }

    public function delete(User $user, Employe $employe): bool
    {
        return $user->role === 'admin';
    }
}
