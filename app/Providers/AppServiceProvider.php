<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share company settings with all views
        View::composer('*', function ($view) {
            $view->with('company_name', Setting::getValue('company_name', 'Tecsisa'));
            $view->with('company_logo', Setting::getValue('company_logo'));
            $view->with('company_footer', Setting::getValue('company_footer', 'Sistema de Gestión de Infraestructura Hospitalaria'));
        });
    }
}
