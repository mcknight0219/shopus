<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

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
}