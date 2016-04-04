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
        $result = app()->make('App\Wechat\HttpServiceInterface')
            ->request('POST', 'merchant/category/getsub', [cate_id => $event->catetory]);

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
