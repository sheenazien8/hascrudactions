<?php

namespace Sheenazien8\Hascrudactions\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static \Sheenazien8\Hascrudactions\Helpers\Response success(array $data, int $code = 200): JsonResponse
 * @method static \Sheenazien8\Hascrudactions\Helpers\Response clientError(array $data, int $code = 422): JsonResponse
 * @method static \Sheenazien8\Hascrudactions\Helpers\Response serverError(array $data, int $code = 500): JsonResponse
 *
 * @see \Sheenazien8\Hascrudactions\Helpers
 */

class Response extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ResponseHelper';
    }
}
