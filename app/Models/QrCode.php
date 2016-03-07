<?php

namespace App;

use App\Wechat\QrCodeService;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $table = 'qr_codes';

    public $timestamps = false;

    protected $fillable = [
        'ticket', 'url'
    ];

    const SCENE_SUBSCRIBE_ID = 1000;
}
