<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            collect(app()->make('Api')->request('GET', 'groups/get')->get('groups'))
                ->each(function ($item) {
                    DB::table('groups')->insert([
                        'id'    => $item['id'],
                        'name'  => $item['name']
                    ]);
                });
        } catch (\Exception $e) {
            echo $e->getMessage();   
        }
    }
}
