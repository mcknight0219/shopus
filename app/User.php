<?php

namespace App;

use Profile;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Check if user owns the product
     *
     * @param   $id
     * @return  Boolean
     */
    public function has($id)
    {
        foreach( $this->products() as $product ) {
            if( $product->id === $id ) return true;
        }
        return false;
    }
}
