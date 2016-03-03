<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Wechat\QrCodeService;

class QrCode extends Model
{
    protected $table = 'qr_codes'

    public $timestamps = false;
}
