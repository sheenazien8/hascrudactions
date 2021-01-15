<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Sheenazien8\Hascrudactions\Traits\GetStubTrait;

class CreateHascruActionCommand extends Command
{
    use GetStubTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hascrudaction:make
                            {model : Passing Model for crud automation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud Action';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $model = $this->argument('model');
            $viewPath = Str::lower($model);
            $repositoryClass = "{$model}Repository";
            if (function_exists('config_path')) {
                if (!File::exists(app_path("Http/Requests/{$model}"))) {
                    // path does not exist
                    File::makeDirectory(app_path("Http/Requests/{$model}"), 0777, true, true);
                    $this->generateRequest($model);
                } else {
                    $this->generateRequest($model);
                }
            }
            $this->generateRepository($repositoryClass, $model);
            $this->createController($model, $viewPath, $repositoryClass);
            if (function_exists('config_path')) {
                $this->generateView($model);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function generateRequest(string $model): void
    {
        foreach (['Index', 'Store', 'Update', 'BulkDelete'] as $value) {
            $requestClasName = str_replace(
                [
                    '{{className}}',
                    '{{model}}',
                ],
                [
                    $value,
                    $model,
                ],
                $this->getStub('Request')
            );
            file_put_contents(app_path("Http/Requests/{$model}/{$value}.php"), $requestClasName);
        }
        $this->info("Request For the $model is created");
        $this->info("Add Your Rule In App/Http/Requests/{$model}");
    }

    private function generateRepository(string $repositoryClass, string $model): void
    {
        Artisan::call('hascrudaction:repository', [
            'name' => $repositoryClass,
            '--model' => $model
        ]);
        $this->info("Repository $model is created");
    }

    private function createController(string $model, string $viewPath, string $repositoryClass): void
    {
        $controllerName = "{$model}Controller";
        $requestClasName = str_replace(
            [
                '{{model}}',
                '{{controllerName}}',
                '{{viewpath}}',
                '{{repositoryClass}}',
            ],
            [
                $model,
                $controllerName,
                $viewPath,
                $repositoryClass,
            ],
            function_exists('config_path') ? $this->getStub('HasCrudAction') : $this->getStub('LumenHasCrudAction')
        );
        file_put_contents(app_path("Http/Controllers/{$controllerName}.php"), $requestClasName);
        $this->info("Controller $controllerName is created");
    }

    private function generateView(string $model)
    {
        $model = Str::lower($model);
        Artisan::call('hascrudaction:view', [
            'folder_name' => $model,
        ]);
        $this->info("View for $model is created");
    }
}
