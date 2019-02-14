<?php

namespace App\Providers;

use App\Device;
use App\Observers\BroadcastingModelObserver;
use App\PapercutStatuses;
use App\Printer;
use App\Resolution;
use App\Ticket;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Device::observe(BroadcastingModelObserver::class);
        PapercutStatuses::observe(BroadcastingModelObserver::class);
        Printer::observe(BroadcastingModelObserver::class);
        Ticket::observe(BroadcastingModelObserver::class);
        Resolution::observe(BroadcastingModelObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
