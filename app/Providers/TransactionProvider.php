<?php

namespace App\Providers;

use App\Interfaces\TransferOut;
use App\Services\TransferOutService;
use Illuminate\Support\ServiceProvider;

class TransactionProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // transfer out inject
        $this->app->bind(TransferOut::class, TransferOutService::class);
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
