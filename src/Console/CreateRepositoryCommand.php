<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Sheenazien8\Hascrudactions\Traits\GetStubTrait;

class CreateRepositoryCommand extends Command
{
    use GetStubTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hascrudaction:repository
                            {name : Pass repository name}
                            {--model= : Pass value models (optioanl)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate repository commnad';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        if (!$model) {
            $model = '';
        } else {
            $model = config('hascrudactions.model_folder') ? config('hascrudactions.model_folder') . $model : 'App\\' . $model;
        }
        $this->createRepositoryFile($name, $model);
    }

    private function createRepositoryFile(string $name, string $model)
    {
        $repositoryTemplate = str_replace(
            [
                '{{repositoryName}}',
                '{{model}}'
            ],
            [
                $name,
                $model
            ],
            $this->getStub('Repository')
        );
        if (!File::exists(app_path("Repositories"))) {
            // path does not exist
            File::makeDirectory(app_path("Repositories"), 0777, true, true);
        }
        file_put_contents(app_path("Repositories/{$name}.php"), $repositoryTemplate);
        $this->info("Repository $name is created");
    }
}
