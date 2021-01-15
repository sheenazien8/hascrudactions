<?php

use Illuminate\Support\Str;

if (!function_exists('dash_to_space')) {
    /**
     * Change dash to space
     *
     * @param string $string
     * @param bool $capital (optional)
     *
     * @return string
     */
    function dash_to_space(string $string, bool $capital = false)
    {
        $name = str_replace('-', ' ', $string);
        $name = str_replace('_', ' ', $name);

        return $capital ? Str::upper($name) : $name;
    }
}
if (!function_exists('price_format')) {
    /**
     * price_format
     *
     * @param int $price
     *
     * @return string
     */
    function price_format($price)
    {
        return 'Rp. ' . number_format($price, 0, ',', '.');
    }
}
if (!function_exists('get_lang')) {
    /**
     * set lang
     *
     * @return void
     */
    function get_lang()
    {
        if (function_exists('session')) {
            app()->setLocale(session()->get('locale') ?? 'en');
        }
    }
}
if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('app_is')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $app
     * @return bool
     */
    function app_is(string $app = ''): bool
    {
        if ($app == 'lumen') {
            return !function_exists('config_path');
        }
        if ($app == 'laravel') {
            return function_exists('config_path');
        }
    }
}

if (!function_exists('config_path')) {

    /**
     * Create a rest hascrud route
     *
     * @param string $path
     * @param string|array $controller
     * @param array $options
     * @param array $resource_options
     * @param bool $resource
     */
    function hascrud($path, $controller = null, $options = [], $resource_options = [], $resource = true)
    {
        global $app;
        $slugs = explode('.', $path);
        /* $prefixSlug = str_replace('.', "/", $path); */
        $className = implode("", array_map(function ($s) {
            return ucfirst(Str::singular($s));
        }, $slugs));

        /**
         * get method items
         */
        function g($method, $name, $pathExt = '')
        {
            return ['method' => $method, 'name' => $name, 'pathExt' => $pathExt];
        }

        $customRoutes = $defaults = ['bulkDestroy'];

        if (isset($options['only'])) {
            $customRoutes = array_intersect($defaults, (array) $options['only']);
        } elseif (isset($options['except'])) {
            $customRoutes = array_diff($defaults, (array) $options['except']);
        }

        foreach ($customRoutes as $route) {
            $routeSlug = "{$route}/{$path}";
            $mapping = ['as' => $path . ".{$route}", 'uses' => "{$path}Controller@{$route}"];
            if ($controller) {
                $mapping = ['as' => $path . ".{$route}", 'uses' => "{$controller}@{$route}"];
            } else {
                $mapping = ['as' => $path . ".{$route}", 'uses' => "{$className}Controller@{$route}"];
            }

            if (in_array($route, ['bulkDestroy'])) {
                $app->router->delete($routeSlug, $mapping);
            }
        }
        if ($resource) {
            /**
             * Restful items.
             */
            $restfulMethods = [
                g('get', 'index'),
                g('get', 'show', '/{model:\d+}'),
                g('post', 'store'),
                g('put', 'update', '/{id}'),
                g('delete', 'destroy', '/{id}'),
            ];

            foreach ($restfulMethods as $restItem) {
                if (isset($options['only'])) {
                    if (!in_array($restItem['name'], $options['only'])) {
                        continue;
                    }
                }
                if (isset($options['except'])) {
                    if (in_array($restItem['name'], $options['except'])) {
                        continue;
                    }
                }

                if ($controller) {
                    $action = $controller . '@' . $restItem['name'];
                } else {
                    $action = "{$className}Controller" . '@' . $restItem['name'];
                }
                $app->router->{$restItem['method']}($path . $restItem['pathExt'], array_merge([
                    'as' => $restItem['name'],
                    'uses' => $action,
                ], $resource_options));
            }
        }
    }
}
