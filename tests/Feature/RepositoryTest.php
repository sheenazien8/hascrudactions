<?php

namespace Sheenazien8\Hascrudactions\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;
use Sheenazien8\Hascrudactions\Tests\Repositories\TestingModelRepository;
use Sheenazien8\Hascrudactions\Tests\database\migrations\CreateTestingTables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Sheenazien8\Hascrudactions\Tests\Models\TestingModel;

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
    public function can_get_paginate_data()
    {
        $testingRepository = app(TestingModelRepository::class)->paginate(request());

        $this->assertEquals("Illuminate\Pagination\LengthAwarePaginator", get_class($testingRepository));

        $this->assertArrayHasKey('last_page', $testingRepository->toArray());
        $this->assertArrayHasKey('last_page_url', $testingRepository->toArray());
        $this->assertArrayHasKey('next_page_url', $testingRepository->toArray());
        $this->assertArrayHasKey('data', $testingRepository->toArray());
    }

    /** @test */
    public function can_get_all_data()
    {
        $testingRepository = app(TestingModelRepository::class)->get(request());

        $this->assertCount($this->count, $testingRepository, 'Record Has a ' . $this->count);

        app(TestingModelRepository::class)->query()->truncate();
    }

    /** @test */
    public function can_create()
    {
        request()->merge([
            'name' => 'Test',
            'salary' => rand(0000, 9999),
            'join_date' => now()->format('Y-m-d')
        ]);
        app(TestingModelRepository::class)->create(request());

        $this->assertDatabaseHas('testing_tables', request()->all());
    }

    /** @test */
    public function can_update()
    {
        request()->merge([
            'name' => 'Test',
            'salary' => rand(0000, 9999),
            'join_date' => now()->format('Y-m-d')
        ]);

        $createdData = app(TestingModelRepository::class)->create(request());

        request()->merge([
            'name' => 'Test 2',
            'salary' => rand(0000, 9999),
            'join_date' => now()->format('Y-m-d')
        ]);
        $updatedData = app(TestingModelRepository::class)->create(request(), $createdData->id);

        $this->assertEquals('Test 2', $updatedData->name);
    }

    /** @test */
    public function can_destory()
    {
        $id = rand(0, $this->count);
        $findData = app(TestingModelRepository::class)->delete($id);

        $this->assertCount($this->count - 1, app(TestingModelRepository::class)->get(request()));

        $this->assertTrue($findData);
    }

    /** @test */
    public function can_bulk_destroy()
    {
        $data = app(TestingModelRepository::class)->get(request());

        $random_id = range(1, rand(1, $this->count));

        request()->merge([
            'ids' => $random_id
        ]);

        app(TestingModelRepository::class)->bulkDestroy(request());

        $deleted_data = app(TestingModelRepository::class)->get(request());


        $count = $data->count();
        $result  = $count - count($random_id);
        if (count($random_id) > $count) {
            $result  = count($random_id) - $count;
        }

        $this->assertCount($result, $deleted_data);
    }
}
