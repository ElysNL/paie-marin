<?php

namespace App\Providers;

use App\Models\Banque;
use App\Models\Classification;
use App\Models\Compagnie;
use App\Models\Devise;
use App\Models\Fonction;
use App\Models\Pays;
use App\Models\User;
use App\Policies\ReferentielPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);

        // Référentiels partagent ReferentielPolicy (admin/rh CRUD, lecture pour tous)
        Gate::policy(Pays::class, ReferentielPolicy::class);
        Gate::policy(Devise::class, ReferentielPolicy::class);
        Gate::policy(Fonction::class, ReferentielPolicy::class);
        Gate::policy(Classification::class, ReferentielPolicy::class);
        Gate::policy(Compagnie::class, ReferentielPolicy::class);
        Gate::policy(Banque::class, ReferentielPolicy::class);
    }
}
