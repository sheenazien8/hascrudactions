<?php

namespace Sheenazien8\Hascrudactions\Traits;

use Sheenazien8\Hascrudactions\LaTable\DefaultLatable;

/**
 * Trait HasLaTable
 * @author sheenazien8
 */
trait HasLaTable
{
    /**
     * Inject Model for use latable datatable
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Sheenazien8\Hascrudactions\LaTable\DefaultLatable
     */
    public function table($query)
    {
        return $this->latable ? new $this->latable($query) : new DefaultLatable($query);
    }
}
