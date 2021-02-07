<?php

namespace Sheenazien8\Hascrudactions\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;

/**
 * Class CreateRepositotyCommandTest
 * @author sheenazien8
 */
class CreateControllerCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [HascrudactionsServiceProvider::class];
    }

    /** @test */
    public function it_creates_a_new_controller_class()
    {
        $reposityClass = app_path('Http/Controllers/TestController.php');

        // make sure we're starting from a clean state
        if (File::exists($reposityClass)) {
            unlink($reposityClass);
        }

        $this->assertFalse(File::exists($reposityClass));

        Artisan::call('hascrudaction:controller', [
            'name' => 'TestController',
            '--viewpath' => 'test',
            '--repository' => 'TestRepository'
        ]);

        // Assert a new file is created
        $this->assertTrue(File::exists($reposityClass));
    }
}
