<?php

namespace App;

use Auth;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'description'
    ];

    /**
     * Get all photos belong to product.
     *
     * @return [App\ProductPhoto]
     */
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

    /**
     * Sanitize the input currency value
     *
     * @param String $value
     */
    public function setCurrencyAttribute($value)
    {
        $collection = collection(['cad', 'usd', 'yuan']);
        $value = $collection->has(strtolower($value)) ? strtolower($value) : 'yuan';

        $this->attributes['currency'] = $value;
    }

    /**
     * Upload photos to storage system.
     *
     * @param   Illuminate\Http\Request $request
     * @return  App\Product
     */
    public static function savePhotos(Request $request)
    {
    }
}
