<?php

namespace Sheenazien8\Hascrudactions\Tests\Feature;

use CreateHascrudRowsTable;
use CreateHascrudsTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;

/**
 * Class CreateCrudTest
 * @author sheenazien8
 */
class CreateCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        include_once __DIR__ . '/../../database/migrations/create_hascruds_table.php';
        include_once __DIR__ . '/../../database/migrations/create_hascrud_rows_table.php';
        (new CreateHascrudsTable())->up();
        (new CreateHascrudRowsTable())->up();
    }

    protected function getPackageProviders($app)
    {
        return [HascrudactionsServiceProvider::class];
    }

    /** @test */
    public function get_table_exists()
    {
        $this->assertTrue(Schema::hasTable('hascruds'));
        $this->assertTrue(Schema::hasTable('hascrud_rows'));
    }
}
