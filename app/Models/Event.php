<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "events";

	public $timestamps = false;

    public function message()
    {
        return $this->morphOne('App\Models\Message', 'messageable');
    }

    /**
     * If this is a unique message. For event message, we could
     * use the combination of fromUser and createTime.
     *
     * @retun bool
     */
    public function unique()
    {
        return 1 === Message::where('fromUserName', $this->message->fromUserName)->where('createTime', $this->message->createTime)->get()->count();    
    }
	
	// Some subscription event are sent with qr scan information
	public function isQRScanned()
	{
		return $this->event === 'subscribe' && $this->ticket !== null;
	}
	
	public function geolocation()
	{
		if( $this->event === 'LOCATION' ) {
			$geoData = explode(';', $this->eventKey);
			return [
				'Latitude' 	=> $geoData[0],
				'Longitude'	=> $geoData[1],
				'Precision'	=> $geoData[2]
			];
		} else {
			return false;
		}
	}
}
