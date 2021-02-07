<?php

namespace Sheenazien8\Hascrudactions;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/**
 * Class RouteServiceProvider
 * @author sheenazien8
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstraps the package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMacros();
        parent::boot();
    }

    /**
     * inject module method to facede route
     *
     * @return void
     */
    private function registerMacros()
    {
        /**
         * add hascrud static function to Route instance
         *
         * @param string $slug
         * @param string|Controller $controllerClass (optional)
         * @param array $options (optional)
         * @param array $resource_options (optional)
         * @param bool $resource (optional)
         */
        Route::macro('hascrud', function ($slug, $controllerClass = null, $options = [], $resource_options = [], $resource = true) {
            /* dd($controllerClass, $slug, $options); */
            $slugs = explode('.', $slug);
            $prefixSlug = str_replace('.', "/", $slug);
            $className = implode("", array_map(function ($s) {
                return ucfirst(Str::singular($s));
            }, $slugs));

            $customRoutes = $defaults = ['bulkDestroy'];

            if (isset($options['only'])) {
                $customRoutes = array_intersect($defaults, (array) $options['only']);
            } elseif (isset($options['except'])) {
                $customRoutes = array_diff($defaults, (array) $options['except']);
            }

            // Get the current route groups
            $routeGroups = Route::getGroupStack() ?? [];

            // Get the name prefix of the last group
            $lastRouteGroupName = end($routeGroups)['as'] ?? '';

            $groupPrefix = trim(str_replace('/', '.', Route::getLastGroupPrefix()), '.');

            // Check if name will be a duplicate, and prevent if needed/allowed
            if (!empty($groupPrefix) &&
                (blank($lastRouteGroupName) ||
                    config('hascrudactions.allow_duplicate_route', true) ||
                    (!Str::endsWith($lastRouteGroupName, ".{$groupPrefix}.")))
            ) {
                $customRoutePrefix = "{$groupPrefix}.{$slug}";
                $resourceCustomGroupPrefix = "{$groupPrefix}.";
            } else {
                $customRoutePrefix = $slug;

                // Prevent Laravel from generating route names with duplication
                $resourceCustomGroupPrefix = '';
            }

            foreach ($customRoutes as $route) {
                $routeSlug = "{$route}/{$prefixSlug}";
                $mapping = ['as' => $customRoutePrefix . ".{$route}", 'uses' => "{$className}Controller@{$route}"];
                if ($controllerClass) {
                    $mapping = ['as' => $customRoutePrefix . ".{$route}", 'uses' => "{$controllerClass}@{$route}"];
                }

                if (in_array($route, ['bulkDestroy'])) {
                    Route::delete($routeSlug, $mapping);
                }
            }

            if ($resource) {
                Route::group(['as' => $resourceCustomGroupPrefix], function () use ($slug, $className, $resource_options, $controllerClass) {
                    if ($controllerClass) {
                        $controller = $controllerClass;
                    } else {
                        $controller = "{$className}Controller";
                    }
                    Route::resource($slug, $controller, $resource_options);
                });
            }
        });
    }
}
