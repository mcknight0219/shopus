<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\ProductPhoto;
use Storage;
use Log;

class StoreProductPhotos extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $_productId;
    protected $_sessionId;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr)
    {
        $this->_productId = $arr['product_id'];
        $this->_sessionId = $arr['session_id'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dir = 'tmp/' . $this->_sessionId;
        if(! $this->exists($dir) ) {
            Log::warning('No files uploaded for session ' . $this->_sessionId);
            return;
        }  

        foreach( Storage::disk('local')->files($dir) as $file ) {
            $type = pathinfo($file)['filename'];
            $content = file_get_contents(storage_path() . '/app/' . $file);
            $name = md5($content) . '.' . pathinfo($file, PATHINFO_EXTENSION);
            Storage::disk('s3')->put($name, $content);
            Storage::disk('local')->delete($file);

            $photo = new ProductPhoto();
            $photo->type = $type;
            $photo->location = $name;
            $photo->product_id = $this->_productId;
            try {
                $photo->save();
            } catch( Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    protected function exists($dir)
    {
        $parts = explode('/', $dir);
        $parts = array_values(
            array_filter($parts, function($part) { return strlen($part) > 0; })
        );

        if( count($parts) > 2 ) {
            Log::warning('_hasDirectory() only supports two level recursion');
            return false;
        }

        if( in_array($parts[0], Storage::disk('local')->directories('/')) ) {
            if( count($parts) === 1) return true;
            else {
                return in_array($parts[0] . '/' . $parts[1], Storage::disk('local')->directories('/' . $parts[0]));
            }
        }

        return false;
    }

}
