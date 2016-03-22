<?php

namespace App;

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

    /**
     * Get all products published by the user
     * 
     * @return [App\Product] 
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
