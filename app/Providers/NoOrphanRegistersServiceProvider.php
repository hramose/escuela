<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\NoOrphanRegisters;

class NoOrphanRegistersServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Helpers\Contracts\NoOrphanRegistersContract', function(){

            return new NoOrphanRegisters();

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Helpers\Contracts\NoOrphanRegistersContract'];
    }

}