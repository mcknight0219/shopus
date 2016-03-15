<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Profile;

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
        foreach (Profile::all() as $profile) {
            if ($this->weixinId && $this->weixinId === $profile->weixin) {
                return true;
            }
        }   

        return false;     
    }

    /**
     * Determine if a weixin user has subscribed  
     *
     * @return Boolean
     **/
    static public function isSubscribed($weixin)
    {
        if (strlen($weixin) === 0) {
            return false;
        }

        foreach (Subscriber::all() as $subscriber) {
            if ($subscriber->weixinId && $subscriber->weixinId === $weixin) {
                return true;
            }            
        }
        
        return false;
    }
}
