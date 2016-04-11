<?php

namespace App\Console\Commands;

use Filesystem;
use App\Wechat\MenuService;
use Illuminate\Console\Command;

class MakeMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make customized menu using MenuService. Configuration is read from menu.json';

    /**
     * Create a new command instance.
     *
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
        $config = $this->readConfigurationContent();
        if (! $config) {
            $this->info('Menu configuration file is empty.');
            return;
        }

        with(new MenuService(app()->make('Api')))->create($config);
    }

    /**
     * Read the configuration from local file
     *
     * @return Array
     */
    protected function readConfigurationContent()
    {
        $path = app_path('app/Console/Commands/menu.json');
        return Filesystem::exists($path) ? Filesystem::get($path) : '';
    }
}
