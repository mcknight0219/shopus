<?php

namespace App;

use Log;
use DB;
use Illuminate\Database\Eloquent\Model;

// Events are messages weixin pushed to us when
// 
class Event extends Model
{
    protected $table = 'events';

    /**
     * Create an event from xml data
     * @param  String $xmlRaw 
     * @return [type]         [description]
     */
    public static function fromXml($xmlRaw)
    {
        if( !is_string($xmlRaw) || strlen($xmlRaw) ) {
            Log::warning('xml data should be in string format and not empty');
            return;
        }

        $parser = xml_parser_create();
        if( xml_parse_into_struct($parser, $xmlRaw, $values, $index) === 0 ) {
            Log::warning('Error at parsing xml');
            return;
        }

        try {
            $event = new Event;

        }
    }
}
