<?php

namespace App\Policies;

use App\Models\{User, BulletinPaie};

class BulletinPaiePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BulletinPaie $bulletin): bool
    {
        return true;
    }

    public function delete(User $user, BulletinPaie $bulletin): bool
    {
        return in_array($user->role, ['admin', 'paie']);
    }

    public function export(User $user): bool
    {
        return true;
    }
}
