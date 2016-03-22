<?php

namespace App;

use App\Subscriber;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Profile extends Model
{
    protected $fillable = [
        'weixin', 'city', 'country', 'firstName', 'lastName'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Save the profile photo
     * 
     * @param  Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Boolean 
     */
    public function savePhoto(UploadedFile $file)
    {
        if (!$file->isValid()) {
            return false;
        }

        try {
            $content = file_get_contents($file);
            $name = md5($content);
            $this->photo = $name;
            $this->save();
            Storage::disk('s3')->put($name, $content);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed saving photo to user\'s profile');
            return false;
        }
    }

    /**
     * Check if user has subscribed to our offical account
     * 
     * @return Boolean 
     */
    public function subscribed()
    {
        return !is_null(Subscriber::where('weixinId', $this->weixin)->get());    
    }
}
