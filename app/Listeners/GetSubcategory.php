<?php

namespace App\Listeners;

use Event;
use App\Events\CategoryFound;
use App\Wechat\Store\Category;


class GetSubcategory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CategoryFound  $event
     * @return void
     */
    public function handle(CategoryFound $event)
    {
        $result = app()->make('StoreApi')
            ->request('POST', 'merchant/express/add', ['form_params' => []]);
        var_dump('Hello' . $result);

        if ($result->get('errmsg') === 'success') {
            collect($result->get('cate_list'))->each(function ($cate) use ($event) {
                Category::create([
                    'id'        => $cate['id'],
                    'name'      => $cate['name'],
                    'parent'    => $event->category
                ]);
                Event::fire(new CategoryFound(['id' => $cate['id']]));
            });
        }

    }
}
