<?php

namespace App\Providers;

use App\Models\Member;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\GlobalComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', GlobalComposer::class);
        $locationsList = [
            'distnctUnitName' => Member::select('unit_name')->filterByZone()->distinct()->get(),
            'distnctZoneName' => Member::select('zone_name')->filterByZone()->distinct()->get(),
            'distnctDivisionName' => Member::select('division_name')->filterByZone()->distinct()->get(),
        ];
        View::share('locationsList', $locationsList);
    }
}