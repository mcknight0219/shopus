<?php

namespace App\Listeners;

use App\Events\CateogryFound;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetSubcategory
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CateogryFound  $event
     * @return void
     */
    public function handle(CateogryFound $event)
    {
        app()->make('App\Wechat\HttpServiceInterface')
            ->request('POST', 'merchant/category/getsub', [cate_id => $event->catetory])    
    }
}
