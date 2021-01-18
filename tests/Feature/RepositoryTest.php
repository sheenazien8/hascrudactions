<?php

namespace Sheenazien8\Hascrudactions\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;
use Sheenazien8\Hascrudactions\Tests\Repositories\TestingModelRepository;
use Sheenazien8\Hascrudactions\Tests\TestingModel;
use Sheenazien8\Hascrudactions\Tests\database\migrations\CreateTestingTables;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $count = 10;

    protected function setUp(): void
    {
        parent::setUp();
        (new CreateTestingTables())->up();
        $this->withFactories(__DIR__ . '/../database/factories');
        factory(TestingModel::class, $this->count)->create();
    }

    protected function getPackageProviders($app)
    {
        return [HascrudactionsServiceProvider::class];
    }

    /** @test */
    public function can_get_all_data_with_repository()
    {
        $testingRepository = app(TestingModelRepository::class)->get(request());

        $this->assertCount($this->count, $testingRepository, 'Record Has a ' . $this->count);
    }
}
