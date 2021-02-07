<?php

namespace Sheenazien8\Hascrudactions\Tests\Feature;

use CreateHascrudRowsTable;
use CreateHascrudsTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use Sheenazien8\Hascrudactions\HascrudactionsServiceProvider;
use Sheenazien8\Hascrudactions\Tests\database\migrations\CreateUsersTables;
use Sheenazien8\Hascrudactions\Tests\Models\User;

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
        (new CreateUsersTables())->up();
        (new CreateHascrudsTable())->up();
        (new CreateHascrudRowsTable())->up();
        $this->withFactories(__DIR__ . '/../database/factories');
        factory(User::class)->create();
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

    /** @test */
    public function it_client_error()
    {
        $data = array_merge($this->data(), [
            'slug' => '',
            'req' => [
                'type' => 'unique',
                'strategy' => 'full_url'
            ]
        ]);

        $response = $this->actingAs($this->user())->post(route('hascrud.store'), $data);

        $response->assertSessionHasErrors(['slug', 'hascrud_rows.*.update_validation']);
    }

    /** @test */
    public function it_succes_create_crud_actions()
    {
        $this->withoutExceptionHandling();
        $controllerClass = app_path('Http/Controllers/FooController.php');

        // make sure we're starting from a clean state
        if (File::exists($controllerClass)) {
            unlink($controllerClass);
        }

        $this->assertFalse(File::exists($controllerClass));

        $repositoryClass = app_path('Repositories/FooRepository.php');

        // make sure we're starting from a clean state
        if (File::exists($repositoryClass)) {
            unlink($repositoryClass);
        }

        $this->assertFalse(File::exists($repositoryClass));

        $data = $this->data();

        $response = $this->actingAs($this->user())->post(route('hascrud.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        /**
         * what i expected
         *
         * Crud With GUI
         *
         * Create The Controller with (opition) Request
         * you can choose what the strategy what you want
         * - Array
         * - Single Class
         * - Full URL
         *
         * Create Request where you choose
         * Choose Request Validation is unique or same
         * Insert type, display_name, is_multiple, is_required, show_in_read, show_in_edit, show_in_create
         * Insert Validation to table as json
         * Create Validation Rule
         * Create The Repository File
         * Create View File
         *
         */

        $this->assertDatabaseHas('hascruds', [
            'slug' => 'foo',
            'controller' => 'FooController',
            'show_singular_name' => 'Foo',
            'show_plural_name' => 'Foos',
            'request' => json_encode(['type' => 'same', 'strategy' => 'single_class']),
            'permission' => false,
            'server_side' => true
        ]);

        $this->assertDatabaseHas('hascrud_rows', [
            'type' => ['text'],
            'collumn' => ['name'],
            'display_name' => ['Name'],
            'is_required' => [true],
            'is_multiple' => [false],
            'show_in_read' => [true],
            'show_in_create' => [true],
            'show_in_edit' => [true]
        ]);

        $this->assertTrue(File::exists($repositoryClass));
        $this->assertTrue(File::exists($controllerClass));
    }

    private function data(): array
    {
        return [
            'slug' => 'foo',
            'controller' => 'FooController',
            'req' => [
                'type' => 'same',
                'strategy' => 'single_class',
            ],
            'permission' => false,
            'hascrud_rows' => [
                [
                    'type' => 'text',
                    'collumn' => 'name',
                    'store_validation' => json_encode(['string', 'min:5', 'unique:foos', 'required']),
                    'is_required' => true,
                    'is_multiple' => false,
                    'show_in_read' => true,
                    'show_in_edit' => true,
                    'show_in_create' => true,
                ],
                [
                    'type' => 'text',
                    'collumn' => 'bar',
                    'store_validation' => json_encode(['string', 'min:5', 'unique:foos']),
                    'is_required' => false,
                    'is_multiple' => false,
                    'show_in_read' => true,
                    'show_in_edit' => true,
                    'show_in_create' => true,
                ]
            ]
        ];
    }

    private function user()
    {
        return User::first();
    }
}
