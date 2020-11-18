<?php

namespace Sheenazien8\Hascrudactions;

use Illuminate\Support\ServiceProvider;
use Sheenazien8\Hascrudactions\Console\CreateRepositoryCommand;

class HascrudactionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'hascrudactions');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hascrudactions');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('hascrudactions.php'),
            ], 'config');


            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/hascrudactions'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/hascrudactions'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/hascrudactions'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                CreateRepositoryCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'hascrudactions');

        // Register the main class to use with the facade
        $this->app->singleton('hascrudactions', function () {
            return new Hascrudactions;
        });
    }
}
