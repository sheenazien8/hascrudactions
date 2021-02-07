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
class CreateRepositotyCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [HascrudactionsServiceProvider::class];
    }

    /** @test */
    public function it_creates_a_new_repository_class()
    {
        $reposityClass = app_path('Repositories/FooRepository.php');

        // make sure we're starting from a clean state
        if (File::exists($reposityClass)) {
            unlink($reposityClass);
        }

        $this->assertFalse(File::exists($reposityClass));

        Artisan::call('hascrudaction:repository', [
            'name' => 'FooRepository',
            '--model' => 'Foo'
        ]);

        // Assert a new file is created
        $this->assertTrue(File::exists($reposityClass));

        // Assert the file contains the right contents
        $expectedContents = <<<CLASS
<?php

namespace App\Repositories;

use Sheenazien8\Hascrudactions\Abstracts\Repository as RepositoryAbstract;

class FooRepository extends RepositoryAbstract
{
    /**
     * Inject Model To Repository
     */
    public function __construct()
    {
        parent::__construct(new \App\Foo());
    }
}

CLASS;

        $this->assertEquals($expectedContents, file_get_contents($reposityClass));
    }
}
