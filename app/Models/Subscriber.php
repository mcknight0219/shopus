<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Profile;

class Subscriber extends Model
{
    protected $table = 'subscribers';

    protected $fillable = ['openId'];

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

    }
}