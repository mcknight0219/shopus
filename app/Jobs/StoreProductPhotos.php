<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $this->_sessionId = $arr['session'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dir = 'tmp/' . $this->_sessionId;
        if(! exists($dir) ) {
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

}
