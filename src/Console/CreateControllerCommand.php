<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Sheenazien8\Hascrudactions\Traits\GetStubTrait;

/**
 * Class CreateControllerCommand
 * @author sheenazien8
 */
class CreateControllerCommand extends Command
{
    use GetStubTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hascrudaction:controller
                            {name : Assifn your controller name} {--viewpath= : Define the view path} {--repository= : Define the repository class}';

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
        $controllerName = $this->argument('name');
        $viewPath = $this->option('viewpath');
        $repositoryClass = $this->option('repository');
        $this->createController($controllerName, $viewPath, $repositoryClass);
    }

    private function createController(string $controllerName, ?string $viewPath, ?string $repositoryClass): void
    {
        if (!$viewPath) {
            $viewPath = 'example_view';
        }
        if (!$repositoryClass) {
            $repositoryClass = 'RepositoryClass';
        }
        $requestClasName = str_replace(
            [
                '{{controllerName}}',
                '{{viewpath}}',
                '{{repositoryClass}}',
            ],
            [
                $controllerName,
                $viewPath,
                $repositoryClass,
            ],
            function_exists('config_path') ? $this->getStub('HasCrudAction') : $this->getStub('LumenHasCrudAction')
        );
        file_put_contents(app_path("Http/Controllers/{$controllerName}.php"), $requestClasName);
        $this->info("Controller $controllerName is created");
    }
}
