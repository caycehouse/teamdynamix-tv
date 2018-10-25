<?php

namespace App\Providers;

use App\Device;
use App\Printer;
use App\Ticket;
use App\Observers\BroadcastingModelObserver;
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
        Printer::observe(BroadcastingModelObserver::class);
        Ticket::observe(BroadcastingModelObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
