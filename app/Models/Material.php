<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['mediaId', 'type', 'isPermanent'];

    /**
     * Check if material is expired (only works for temporary)
     *
     * @return Boolean 
     */
    public function expired()
    {
        if( !$this->isPermanent ) {
           $diff = time() - strtotime($this->created_at);
           return $diff > 3 * 24 * 3600;    // temporary material expires in 3 days
        }
        return FALSE;
    } 
}
