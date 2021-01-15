<?php

namespace Sheenazien8\Hascrudactions\Helpers;

use Illuminate\Support\Str;


/**
 * Class Helpers
 * @author sheenazien8
 */
class StrHelper
{
    public static function dash_to_space(string $string, bool $capital = false)

    {
        $name = str_replace('-', ' ', $string);
        $name = str_replace('_', ' ', $name);

        return $capital ? Str::upper($name) : $name;
    }
}
