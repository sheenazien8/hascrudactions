<?php

namespace Sheenazien8\Hascrudactions\Traits;

use Sheenazien8\Hascrudactions\LaTable\DefaultLatable;

/**
 * Trait HasLaTable
 * @author sheenazien8
 */
trait HasLaTable
{
    public function table($query)
    {
        return $this->latable ? new $this->latable($query) : new DefaultLatable($query);
    }
}
