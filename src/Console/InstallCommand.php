<?php

namespace Sheenazien8\Hascrudactions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hascrudaction:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate install commnad';

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
        /* FIXME: cant provide the config <22-11-20 sheenazien8> */
        Artisan::call('vendor:publish', [
            '--provider' => Sheenazien8\Hascrudactions\HascrudactionsServiceProvider::class,
        ]);
        $this->info('Command success');
    }
}

