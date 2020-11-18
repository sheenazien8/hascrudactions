<?php

namespace Sheenazien8\Hascrudactions\Traits;

use App\DataTables\DefaultTable;

/**
 * Trait HasLaTable
 * @author sheenazien8
 */
trait HasLaTable
{
    public function table($query)
    {
        return $this->latable ? new $this->latable($query) : new DefaultTable($query);
    }
}
