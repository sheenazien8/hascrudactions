<?php

namespace Sheenazien8\Hascrudactions;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Sheenazien8\Hascrudactions\Console\CreateHascruActionCommand;
use Sheenazien8\Hascrudactions\Console\CreateRepositoryCommand;
use Sheenazien8\Hascrudactions\Console\CreateViewCommand;
use Sheenazien8\Hascrudactions\Console\InstallCommand;
use Sheenazien8\Hascrudactions\Helpers\Response;
use Sheenazien8\Hascrudactions\Traits\InjectBladeResolve;
use Sheenazien8\Hascrudactions\Views\Components\Button;
use Sheenazien8\Hascrudactions\Views\Components\Form;
use Sheenazien8\Hascrudactions\Views\Components\IndexTable;

class HascrudactionsServiceProvider extends ServiceProvider
{
    use InjectBladeResolve;

    /**
     * @var string[] $providers
     */
    protected $providers = [
        RouteServiceProvider::class
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'hascrudactions');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'hascrudactions');

        $this->loadViewComponentsAs('components', [
            'index-table' => IndexTable::class,
            'form' => Form::class,
            'button' => Button::class
        ]);

        if ($this->app->runningInConsole()) {
            if (function_exists('config_path')) {
                $this->publishes([
                    __DIR__ . '/../config/config.php' => config_path('hascrudactions.php'),
                ], 'config');
            }

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/hascrudactions'),
            ], 'views');

            // Publishing the translation files.
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/hascrudactions'),
            ], 'lang');

            // Registering package commands.

            if (function_exists('config_path')) {
                $this->commands([
                    CreateRepositoryCommand::class,
                    CreateHascruActionCommand::class,
                    InstallCommand::class,
                    CreateViewCommand::class,
                ]);
            } else {
                $this->commands([
                    CreateHascruActionCommand::class,
                    CreateRepositoryCommand::class,
                    InstallCommand::class,
                ]);
            }
        }

        $this->app->bind('ResponseHelper', function () {
            return new Response();
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerProviders();
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'hascrudactions');

        // Register the main class to use with the facade
        $this->app->singleton('hascrudactions', function () {
            return new Hascrudactions;
        });
    }
    /**
     * Registers the package service providers.
     *
     * @return void
     */
    private function registerProviders()
    {
        if (!method_exists(Route::class, 'macro')) { // Lumen
            return;
        }

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}
