<?php

namespace App;

use Log;
use Illuminate\Database\Eloquent\Model;

class QrTicket extends Model
{
    /**
     * @var no need for timestamp
     */
    public $timestamps = false;

    protected $fillable = [
        'scene', 'ticket', 'url'
    ];

    /**
     * Use QrCodeService to create a permanent qr ticket
     *
     * @param  integer $scene
     * @return mixed
     */
    public static function createAndReturn($scene)
    {
        $resp = app()->make('QrService')->createTicket($scene);
        if ($resp->has('errmsg')) {
            Log::info('Error creating qr ticket: '.$resp->get('errmsg'));
            return null;
        }

        $qr = new static([
            'scene'     => $scene,
            'ticket'    => $resp->get('ticket'),
            'url'       => $resp->get('url')
        ]);
        return $qr->save() ? $qr : null; 
    }
}
