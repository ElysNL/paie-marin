<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Crée un utilisateur administrateur par défaut (idempotent).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrateur',
                'password' => env('ADMIN_PASSWORD', Str::random(16)),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }
}