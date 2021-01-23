<?php

namespace Sheenazien8\Hascrudactions\Tests\Repositories;

use Sheenazien8\Hascrudactions\Abstracts\Repository;
use Sheenazien8\Hascrudactions\Tests\Models\TestingModel;

/**
 * Class TestingModelRepository
 * @author sheenazien8
 */
class TestingModelRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new TestingModel());
    }
}
