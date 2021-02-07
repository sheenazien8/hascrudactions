<?php

namespace Sheenazien8\Hascrudactions\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;

class HelpersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [HascrudactionsServiceProvider::class];
    }

    /** @test */
    public function except_last_word()
    {
        $string = 'pre.employee.index';

        $result = except_last_word($string);

        $this->assertEquals('pre.employee', $result);
    }
}
