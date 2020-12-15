<?php

if (! function_exists('dash_to_space')) {
    function dash_to_space(string $string, bool $capital = false)
    {
        $name = str_replace('-', ' ', $string);
        $name = str_replace('_', ' ', $name);

        return $capital ? Str::upper($name) : $name;
    }
}
if (! function_exists('price_format')) {
    function price_format($price)
    {
        return 'Rp. '.number_format($price, 0, ',', '.');
    }
}
if (! function_exists('get_lang')) {
    function get_lang()
    {
        app()->setLocale(optional(auth()->user() ?? 'en')->localization);
    }
}

