<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Interfaces\EwsDeviceRepositoryInterface::class, \App\Repositories\EwsDeviceRepository::class);
        $this->app->bind(\App\Interfaces\EwsDeviceMeasurementRepositoryInterface::class, \App\Repositories\EwsDeviceMeasurementRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
