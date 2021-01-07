<?php

namespace MWI\LaravelFiles;

use MWI\LaravelFiles\MWIFile;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        if (app()->version() < 8) {
            $this->publishes([
                __DIR__.'/app/' => app_path(),
            ], 'models');
        } else {

            $this->publishes([
                __DIR__.'/app/Models/' => app_path('Models'),
            ], 'models');
        }

        $this->publishes([
            __DIR__.'/database/' => database_path(),
        ], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Install::class
            ]);
        }
    }

    public function register()
    {
        $this->app->bind('mwifile', MWIFile::class);
    }
}
