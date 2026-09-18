<?php

namespace App\Policies;

use App\Models\{User, Paie};

class PaiePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Paie $paie): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'paie']);
    }

    public function update(User $user, Paie $paie): bool
    {
        return in_array($paie->statut, ['brouillon', 'calcule'])
            && in_array($user->role, ['admin', 'paie']);
    }

    public function delete(User $user, Paie $paie): bool
    {
        return $paie->statut === 'brouillon'
            && in_array($user->role, ['admin', 'paie']);
    }

    public function calculer(User $user, Paie $paie): bool
    {
        return $paie->statut === 'brouillon'
            && in_array($user->role, ['admin', 'paie']);
    }

    public function valider(User $user, Paie $paie): bool
    {
        return $paie->statut === 'calcule'
            && in_array($user->role, ['admin', 'paie']);
    }

    public function cloturer(User $user, Paie $paie): bool
    {
        return $paie->statut === 'valide'
            && $user->role === 'admin';
    }
}
