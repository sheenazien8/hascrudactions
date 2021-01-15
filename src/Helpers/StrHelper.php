<?php

namespace Sheenazien8\Hascrudactions\Helpers;

use Illuminate\Support\Str;


/**
 * Class Helpers
 * @author sheenazien8
 */
class StrHelper
{
    /**
     * dash_to_space, transform dashcase to space
     *
     * @param string $string
     * @param bool $capital (optional)
     *
     * @return string
     */
    public static function dash_to_space(string $string, bool $capital = false)
    {
        $name = str_replace('-', ' ', $string);
        $name = str_replace('_', ' ', $name);

        return $capital ? Str::upper($name) : $name;
    }
}
