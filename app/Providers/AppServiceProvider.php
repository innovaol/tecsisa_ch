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
        // Cache company settings ONCE, then share with all views
        // Previously: View::composer('*') ran 3 DB queries per every single view/partial (could be 100+ per page!)
        $companyName = null;
        $companyLogo = null;
        $companyFooter = null;

        View::composer('layouts.*', function ($view) use (&$companyName, &$companyLogo, &$companyFooter) {
            if ($companyName === null) {
                $settings = Setting::whereIn('key', ['company_name', 'company_logo', 'company_footer'])->pluck('value', 'key');
                $companyName = $settings['company_name'] ?? 'Tecsisa';
                $companyLogo = $settings['company_logo'] ?? null;
                $companyFooter = $settings['company_footer'] ?? 'Sistema de Gestión de Infraestructura Hospitalaria';
            }
            $view->with('company_name', $companyName);
            $view->with('company_logo', $companyLogo);
            $view->with('company_footer', $companyFooter);
        });
    }
}
