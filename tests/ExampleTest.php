<?php

namespace Sheenazien8\Hascrudactions\Tests;

use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [HascrudactionsServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
