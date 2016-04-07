<?php

namespace App\Models;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'subscribers';

    protected $fillable = ['openId'];

    protected $attributes = [
        'unsubscribed' => false
    ];
    
    /**
     * If user is subscribed from our service
     *
     * @return Boolean 
     */
    public function subscribed()
    {
        return ! $this->unsubscribed;
    }

}
