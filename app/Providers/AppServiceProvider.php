<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\StoredProcedureRepository;
use App\Repositories\StoredProcedureRepositoryInterface;
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
