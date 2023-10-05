<?php

namespace App\Providers;

use App\Interfaces\ReceiveGate;
use App\Services\ReceiveGateService;
use Illuminate\Support\ServiceProvider;

class ReceiveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReceiveGate::class, ReceiveGateService::class);
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
