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
                            {action : create|show|query|rename|delete}';

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
     * @param \Closure  $request
     * @param \Closure  $output
     */
    protected function runCommand(\Closure $request, \Closure $output) 
    {
        try {
            $output($request());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        switch ($this->argument('action')) {
            case 'show':
                $this->runCommand(
                    function() {
                        return $this->service->request('GET', 'groups/get');
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
                $openId = $this->ask('openid: ');
                $this->runCommand(
                    function() use($openId) {
                        return $this->service->request('POST', 'groups/getid', [
                            'json' => [
                                'openid' => $openId
                            ]
                        ]);
                    },
                    function($resp) {
                        $this->sayError($resp);
                        $this->info("Group Id is {$resp->get('groupid')}");
                    }
                );
                break;
            case 'rename':
                $id     = $this->ask('group id [assigned by Weixin]: ');
                $name   = $this->ask('new name: ');
                $this->runCommand(
                    function() use($id, $name) {
                        return $this->service->request('POST', 'groups/update', [
                            'json' => [
                                'group' => [
                                    'id'    => $id,
                                    'name'  => $name
                                ]
                            ]
                        ]);
                    },
                    function($resp) {
                        $this->sayError($resp);
                        $this->info('Successfully rename a group');
                    }
                );
                break;
            case 'delete':
                $id = $this->ask('group id [assigned by Weixin]: ');
                $this->runCommand(
                    function() use($id) {
                        return $this->service->request('POST', 'groups/delete', [
                            'json' => [
                                'group' => [
                                    'id' => $id
                                ]
                            ]
                        ]);
                    },
                    function($resp) {
                        $this->sayError($resp);
                        $this->info('Successfully delete a group');    
                    }
                );
                break;
            case 'create':
                $name = $this->ask('name: ');
                $this->runCommand(
                    function() use($name) {
                        return $this->service->request('POST', 'groups/create',[
                            'json' => [
                                'group' => [
                                    'name' => $name
                                ]
                            ]
                        ]);
                    },
                    function($resp) {
                        $this->sayError($resp);
                        $this->info("Create a group: {$resp->get('group')['id']} {$resp->get('group')['name']}");    
                    } 
                );
                break;
            default:
                $this->error('Unknown action: '.$this->argument('action'));
        }
    }

    // Display the error in response if there is anyone
    protected function sayError($response)
    {
        if ($response->get('errmsg', 'ok') !== 'ok') {
            $this->error("[{$response->get('errcode')}]: {$response->get('errmsg')}");
            exit();
        }
    }
}
