<?php

namespace App\Console\Commands;

use App\Wechat\MenuService;
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
                            {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage the groups for official account.';

    /**
     * @var \App\Wechat\HttpServiceInterface
     */
    protected $service;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = app()->make('Api');
    }

    /**
     * Run the request closure and pass the results to output closure to display
     *
     * @param \Closure $request
     * @param \Closure $output
     */
    protected function runCommand(\Closure $request, \Closure $output)
    {
        try {
            $output($request($this->argument('param')));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        switch ($this->argument('command')) {
            case 'show':
                $this->runCommand(
                    function($arg) use($this) {
                        return $this->service->request('GET', 'groups/get', []);
                    },
                    function($resp) {
                        $this->sayError($resp);
                        collect($resp->get('groups'))->each(function ($item) {
                            $this->info("{$item['name']}({$item['id']}) count: {$item['count']}");
                        });
                    }
                );
                break;
            case 'query':
                $this->runCommand(
                    function($openId) use($this) {
                        return $this->service->request('POST', 'groups/getid', [
                            'json' => [
                                'openid' => $openId
                            ]
                        ]);
                    },
                    function($resp) use($this) {
                        $this->sayError($resp);
                        $this->info("Group Id is {$resp->get('groupid')}");
                    }
                );
                break;
            case 'rename':
                $this->runCommand(
                    function
                );
                break;
        }
    }

    /**
     * Rename an existing group
     *
     * @param integer $groupId
     * @param string  $newName
     */
    protected function rename($groupId, $newName)
    {
        try {
            $resp = $this->service->request('POST', 'groups/update', [
               'json' => [
                   'id'     => $groupId,
                   'name'   => $newName
               ]
            ]);
            $this->sayError($resp);
            $this->info('Successfully rename a group');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Delete an existing group
     *
     * @param integer $groupId
     */
    protected function delete($groupId)
    {
        try {
            $resp = $this->service->request('POST', 'groups/delete', [
                'json' => [
                    'id' => $groupId
                ]
            ]);
            $this->sayError($resp);
            $this->info('Successfully deleted a group');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Create a group with certain name
     *
     * @param string $name
     */
    protected function create($name)
    {
        try {
            $resp = $this->service->request('POST', 'groups/create', [
                'json' => [
                    'group' => ['name' => $name]
                ]
            ]);
            $this->sayError($resp);
            $this->info("Created group: {$resp->get('group')['id']}  {$resp->get('group')['name']}");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // Display the error in response if there is anyone
    protected function sayError($response)
    {
        if ($response->get('errmsg') !== 'ok') {
            $this->error("[{$response->get('errcode')}]: {$response->get('errmsg')}");
            exit();
        }
    }
}
