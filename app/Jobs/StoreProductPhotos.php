<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\ProductPhoto;

use Log;
use Storage;

class StoreProductPhotos extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $_content;
    protected $_ext;
    // product id
    protected $_id;
    protected $_type;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr)
    {
        $this->_id      = $arr['product_id'];
        $this->_ext     = $arr['ext'];
        $this->_type    = $arr['type'];
        $this->_content = $arr['content'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $name = md5($this->_content) . '.' . $this->_ext;
        Storage::disk('s3')->put($name, $this->_content);
        try {
            $photo = new ProductPhoto();
            $photo->type = $this->_type;
            $photo->location = $name;
            $photo->product_id = $this->_id;

            $photo->save();
        } catch(Exception $e) {
            // retry or just report?
            Log::error('Cannot create product phot: ' . $e->getMessage());
        }
    }

    public function failed()
    {
        Log::info('Error pushing the queue');
    }
}
