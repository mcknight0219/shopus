<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Handle the management of groups for official account.
 *
 * @package App\Console\Commands
 */
class ManageGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manage-group
                            {command: create|show|query|rename|delete}
                            {args}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage the groups for official account.';

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
