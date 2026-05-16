<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('access-admin',     fn ($u) => $u->hasRole('admin'));
        Gate::define('access-captain',   fn ($u) => $u->hasAnyRole(['admin', 'captain']));
        Gate::define('access-kagawad',   fn ($u) => $u->hasAnyRole(['admin', 'captain', 'kagawad']));
        Gate::define('access-secretary', fn ($u) => $u->hasAnyRole(['admin', 'secretary']));
        Gate::define('access-tanod',     fn ($u) => $u->hasAnyRole(['admin', 'tanod']));
        Gate::define('access-resident',  fn ($u) => $u->hasRole('resident'));
    }
}
