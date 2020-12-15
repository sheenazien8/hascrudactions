<?php

namespace Sheenazien8\Hascrudactions\Exceptions;

use Exception;

class ServiceActionsException extends Exception
{
    public function render($message)
    {
        abort(500, $message);
    }
}
