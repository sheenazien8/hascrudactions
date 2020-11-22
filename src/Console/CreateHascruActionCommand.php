<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateHascruActionCommand extends Command
{
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
     * @return mixed
     */
    public function handle()
    {
        $model = $this->argument('model');
        $viewPath = Str::lower($model);
        if(!File::exists(app_path("Http/Requests/{$model}"))) {
            // path does not exist
            File::makeDirectory(app_path("Http/Requests/{$model}"), 0777, true, true);
            $this->createRequest($model);
        } else {
            $this->createRequest($model);
        }
        Artisan::call('make:repository', [
            'name' => "{$model}Repository",
            '--model' => $model
        ]);
    }

    protected function getStub($type) {
        return file_get_contents(__DIR__ . "/../../resources/stubs/$type.stub");
    }

    private function createRequest(string $model)
    {
        foreach (['Index', 'Store', 'Update', 'BulkDelete'] as $value) {
            $requestClasName = str_replace([
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
}
