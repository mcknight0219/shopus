<?php

namespace App;

use Log;
use Storage;
use App\Models\Subscriber;
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
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Boolean 
     */
    public function savePhoto(UploadedFile $file)
    {
        if (!$file->isValid()) {
            return false;
        }

        try {
            $this->photo = md5_file($file->getRealPath());
            $this->save();
            Storage::disk()->put($this->photo, file_get_contents($file->getRealPath()));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed saving photo to user\'s profile: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user has subscribed to our official account
     * 
     * @return Boolean 
     */
    public function subscribed()
    {
        return !is_null(Subscriber::where('openId', $this->weixin)->first());
    }
}
