<?php

namespace Sheenazien8\Hascrudactions;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\ServiceProvider;
use Sheenazien8\Hascrudactions\Console\CreateControllerCommand;
use Sheenazien8\Hascrudactions\Console\CreateHascruActionCommand;
use Sheenazien8\Hascrudactions\Console\CreateLatableCommand;
use Sheenazien8\Hascrudactions\Console\CreateRepositoryCommand;
use Sheenazien8\Hascrudactions\Console\CreateViewCommand;
use Sheenazien8\Hascrudactions\Console\InstallCommand;
use Sheenazien8\Hascrudactions\Helpers\Response;
use Sheenazien8\Hascrudactions\Traits\InjectBladeResolve;
use Sheenazien8\Hascrudactions\Views\Components\Button;
use Sheenazien8\Hascrudactions\Views\Components\Form;
use Sheenazien8\Hascrudactions\Views\Components\IndexTable;
use Sheenazien8\Hascrudactions\Views\Components\Link;

/**
 * @className HascrudactionsServiceProvider
 * @copyright MIT
 * @author sheenazien8
 */
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
        $this->loadPackages();

        $this->publishPackage();

        $this->app->bind('ResponseHelper', function () {
            return new Response();
        });
    }

    /**
     * loadPackages from hascrudactions
     *
     * @return void
     */
    private function loadPackages(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'hascrudactions');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'hascrudactions');
        $this->registerRoutes();

        // other things ...

        $this->loadViewComponentsAs('components', [
            'index-table' => IndexTable::class,
            'form' => Form::class,
            'button' => Button::class,
            'link' => Link::class
        ]);
    }

    /**
     * publishe package when runningInConsole
     *
     * @access private
     * @return void
     */
    private function publishPackage(): void
    {
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

            // Publishing the assets files.
            $this->publishes([
                __DIR__ . '/../resources/assets' => public_path('vendor/hascrudactions'),
            ], 'assets');

            // Registering package commands.
            if (function_exists('config_path')) {
                $this->commands([
                    CreateRepositoryCommand::class,
                    CreateHascruActionCommand::class,
                    InstallCommand::class,
                    CreateViewCommand::class,
                    CreateLatableCommand::class,
                    CreateControllerCommand::class
                ]);
            } else {
                $this->commands([
                    CreateHascruActionCommand::class,
                    CreateRepositoryCommand::class,
                    InstallCommand::class,
                ]);
            }
        }
    }


    /**
     * Route Configuration for hascrudactions
     *
     * @return array
     */
    private function routeConfiguration(): array
    {
        return [
            'prefix' => config('hascrudactions.prefix'),
            'middleware' => config('hascrudactions.middleware')
        ];
    }

    /**
     * register routes hascrudactions
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        FacadesRoute::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
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
