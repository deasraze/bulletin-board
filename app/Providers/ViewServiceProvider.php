<?php

namespace App\Providers;

use App\Http\View\Composers\MenuPagesComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', MenuPagesComposer::class);
    }
}
