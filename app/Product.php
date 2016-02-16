<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Brand;

class Product extends Model
{

    protected $fillable = [
        'name', 'price', 'description'
    ];

    public function photos()
    {
        return $this->hasMany('App\ProductPhoto');
    }

    /**
     * Convert brand name to brand id
     *     
     * @param String $value
     */
    public function setBrandIdAttribute($value)
    {
        $brand = Brand::where('name', $value)->first();
        if( $brand === null ) {
            $brand =new Brand(['name' => $value]);
            $brand->save();
        }
        $this->attributes['brand_id'] = $brand->id;
    }
}
