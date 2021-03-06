<?php

namespace App\Providers;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Banner\Banner;
use App\Entity\Ticket\Ticket;
use App\Entity\User\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

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
        $this->registerPermissions();

        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
    }

    private function registerPermissions(): void
    {
        Gate::define('admin-panel', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('manage-banners', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('manage-tickets', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-regions', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-pages', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-adverts', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('manage-adverts-categories', function (User $user) {
            return ($user->isAdmin() || $user->isModerator());
        });

        Gate::define('show-advert', function (User $user, Advert $advert) {
            return ($user->isAdmin() || $user->isModerator() || $user->id === $advert->user_id);
        });

        Gate::define('manage-own-advert', function (User $user, Advert $advert) {
            return $user->id === $advert->user_id;
        });

        Gate::define('manage-own-banner', function (User $user, Banner $banner) {
            return $user->id === $banner->user_id;
        });

        Gate::define('manage-own-ticket', function (User $user, Ticket $ticket) {
            return $user->id === $ticket->user_id;
        });
    }
}
