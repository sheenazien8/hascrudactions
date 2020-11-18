<?php

namespace Sheenazien8\Hascrudactions\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sheenazien8\Hascrudactions\Skeleton\SkeletonClass
 */
class HascrudactionsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hascrudactions';
    }
}
