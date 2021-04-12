<?php

namespace App\Providers;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-panel', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('manage-adverts', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('show-advert', function (User $user, Advert $advert) {
            return ($user->isAdmin() || $user->isModerator() || $user->id === $advert->user_id);
        });

        Gate::define('manage-own-advert', function (User $user, Advert $advert) {
            return $user->id === $advert->user_id;
        });
    }
}
