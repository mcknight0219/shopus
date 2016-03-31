<?php

namespace App\Models;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'subscribers';

    protected $fillable = ['openId', 'weixinId'];

    protected $attriubtes = [
        'unsubscribed' => false
    ];
    
    /**
     * If user has unsubscribed from our service
     *
     * @return Boolean 
     */
    public function unsubscribed()
    {
        return $this->unsubscribed;
    }

    /**
     * Check if this subscriber is a vendor
     * 
     * @return Boolean
     */
    public function vendor()
    {
        return ! is_null($this->weixinId);    
    }

    /**
     * Determine if a weixin user has subscribed  
     *
     * @return Boolean
     **/
    static public function isSubscribed($weixinId)
    {
        return ! static::where('weixinId', $weixinId)->get()->isEmpty();
    }
}
