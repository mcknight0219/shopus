<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "events";

    public function message()
    {
        return $this->morphOne('Message', 'messageable');
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