<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sheenazien8\Hascrudactions\Traits\GetStubTrait;

/**
 * Class CreateLatableCommand
 * @author sheenazien8
 */
class CreateLatableCommand extends Command
{
    use GetStubTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hascrudaction:latable {name : Pass File Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New Datatable service class';
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
        // transform name file
        $explode = explode('/', $name);
        $className = Str::ucfirst(Str::camel(Arr::last($explode)));
        $nameSpace = array_filter($explode, function ($key) use ($explode) {
            return $key != Arr::last($explode);
        });
        /* $ucfirst = ; */

        $this->createLatableClass($className, $nameSpace);
    }

    private function createLatableClass(string $className, array $nameSpace)
    {
        $humanNameSpace = implode('\\', $nameSpace);
        $fileNameSpace = implode('/', $nameSpace);
        $template = str_replace(
            [
                '{{LaTableClassName}}',
                '{{NameSpace}}'
            ],
            [
                $className,
                $humanNameSpace ? "\\{$humanNameSpace}" : null
            ],
            $this->getStub('Latable')
        );

        if (!File::exists(app_path("Latable"))) {
            if ($fileNameSpace) {
                if (!File::exists(app_path("Latable/{$humanNameSpace}"))) {
                    File::makeDirectory(app_path("Latable/{$fileNameSpace}"), 0777, true, true);
                }
            } else {
                File::makeDirectory(app_path("Latable"), 0777, true, true);
            }
            // path does not exist
        }

        file_put_contents(app_path("Latable/{$humanNameSpace}/{$className}.php"), $template);
        $this->info("Latable $className is created");
    }
}
