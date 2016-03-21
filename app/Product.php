<?php

namespace App;

use Log;
use Auth;
use Storage;
use App\Brand;
use App\ProductPhoto;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'description', 'brand', 'currency'
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
     * Sanitize the input currency value
     *
     * @param String $value
     */
    public function setCurrencyAttribute($value)
    {
        $value = collect(['cad', 'usd', 'yuan'])->has(strtolower($value)) ? strtolower($value) : 'yuan';
        $this->attributes['currency'] = $value;
    }

    /**
     * Upload photos to storage system.
     *
     * @param   Illuminate\Http\Request $request
     * @return  App\Product
     */
    public function savePhotos(Request $request)
    {
        collect(['front', 'back', 'custom1', 'custom2'])->map(function($name) use ($request) { 
            if (!$request->hasFile($name) || !$request->file($name)->isValid()) {
                return;
            }
            
            $content = file_get_contents($request->file($name));
            $name = md5($content);
            try {
                $photo = new ProductPhoto;
                $photo->type = $name;
                $photo->product_id = $this->attributes['id'];
                $photo->location = $name;
                Storage::disk('s3')->put($name, $content);
                $photo->save();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        });
    }
}
