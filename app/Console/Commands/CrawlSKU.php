<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrawlSKU extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl-sku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a list of SKU belonging to categories';

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
    }
}
