<?php

namespace App;

use Storage;
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
            $this->photo = $file->hashName();
            $this->save();
            Storage::disk()->put($this->photo, $file);
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

    public function needRemindSubscribe()
    {
        return ! is_null($profile->weixin) && ! $profile->subscribed;
    }
}
