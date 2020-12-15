<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sheenazien8\Hascrudactions\Traits\GetStubTrait;

class CreateViewCommand extends Command
{
    use GetStubTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hascrudaction:view
                            {folder_name : Pass view folder name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate View';

    /**
     * assgin folder name
     *
     * @var string
     */
    private $folderName;

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
        try {
            $folderName = $this->argument('folder_name');
            $resourcePath = resource_path("views/{$folderName}");
            foreach (['Index', 'Create', 'Edit', 'Show'] as $value) {
                $this->createView($resourcePath, $value);
            }
            $this->info("View for {$this->argument('folder_name')} is created!");
        } catch (\Exception $e) {
            if(File::exists($resourcePath)) {
                File::deleteDirectories($resourcePath);
            }
            $this->info($e->getMessage());
        }
    }

    /**
     * createView
     *
     * @params string $resourcePath
     * @params string $value
     *
     * @return void
     */
    private function createView(string $resourcePath, string $value): void
    {
        if(!File::exists($resourcePath)) {
            // path does not exist
            File::makeDirectory($resourcePath,  0777, true, true);
        }
        switch ($value) {
            case 'Index':
                $this->generateComponents($this->argument('folder_name'), $resourcePath, 'Table');
                $search = ['{{folder_name}}'];
                $replace = $this->argument('folder_name');
                $this->info('Create Index View');
                break;
            case 'Create':
                $this->generateComponents($this->argument('folder_name'), $resourcePath, 'Form');
                $search = ['{{folder_name}}'];
                $replace = $this->argument('folder_name');
                $this->info('Create Create View');
                break;
            case 'Edit':
                $search = ['{{folder_name}}'];
                $replace = $this->argument('folder_name');
                $this->info('Create Edit View');
                break;
            case 'Show':
                $search = ['{{folder_name}}'];
                $replace = $this->argument('folder_name');
                $this->info('Create Show View');
                break;
        }
        $bladeTemplate = str_replace($search, $replace, $this->getStub("View{$value}"));
        $fileName = Str::lower($value);
        file_put_contents("{$resourcePath}/{$fileName}.blade.php", $bladeTemplate);
    }

    /**
     * generateComponents
     *
     * @params string $folderName
     * @params string $resourcePath
     * #params string $stub
     *
     * @return string
     */
    private function generateComponents(string $folderName, string $resourcePath, string $stub): string
    {
        $search = ['{{folder_name}}'];
        $replace = $this->argument('folder_name');
        $resourcePath = "{$resourcePath}/components";
        if(!File::exists($resourcePath)) {
            File::makeDirectory($resourcePath,  0777, true, true);
        }
        $bladeTemplate = str_replace($search, $replace, $this->getStub("{$stub}"));
        $stub = Str::lower($stub);
        file_put_contents("{$resourcePath}/{$stub}.blade.php", $bladeTemplate);

        return $bladeTemplate;
    }
}
