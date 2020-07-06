<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MailService;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\MailService', function ($app) {
            return new MailService();
        });
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
