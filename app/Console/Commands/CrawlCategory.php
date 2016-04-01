<?php

namespace App\Console\Commands;

use Event;
use App\Events\CategoryFound;
use Illuminate\Console\Command;

class CrawlCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl-category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a list of all categories';

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
        // Get the ball rolling .
        Event::fire(new CategoryFound(['id' => 1]));
    }
}
