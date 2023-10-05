<?php

namespace App\Providers;

use App\Interfaces\ReceiveGate;
use App\Repositories\StoredProcedureRepository;
use App\Repositories\StoredProcedureRepositoryInterface;
use App\Services\ReceiveGateService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StoredProcedureRepositoryInterface::class, StoredProcedureRepository::class);
       
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
