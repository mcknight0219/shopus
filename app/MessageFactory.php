<?php

namespace App;

use App\Models\Event;
use App\Models\Message;
use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Support\Str;

class MessageFactory
{
    /**
     * common messages fields
     * @var array
     */
    protected $common= ['toUserName', 'fromUserName', 'createTime', 'msgType'];

    /**
     * Create a message from raw xml input
     *
     * @param String $xmlStr
     * @param String $kind
     */
    public function create($xmlStr, $kind)
    {
        $factoryMethod = collect([
            'inbound'   => 'createInbound',
            'outbound'  => 'createOutbound',
            'event'     => 'createEvent'
        ])->get($kind, 'inbound');

        return $this->$factoryMethod(
            json_decode(
                json_encode((array)simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA)),
                true
            )
        );
    }

    /**
     * Create parts of message common to all 3 types
     *
     * @param $fields
     * @return Message|null
     */
    protected function createBasic(Array $fields)
    {
        $message = new Message;
        collec($this->common)->map(function ($name) use ($message, $fields) {
            $message->$name = $fields[ucfirst($name)];
        });

        $message->save();
        return $message;
    }

    /**
     * Create incoming messages
     *
     * @param array $fields
     * @return Message|null
     */
    protected function createInbound(Array $fields)
    {   
        $basic = $this->createBasic($fields);

        $inbound = new Inbound;
        $inbound->msgId = $fields['MsgId'];
        $inbound->content = collect($fields)
            ->filter(function ($name) { return ! in_array($name, array_merge($this->common, ['MsgId'])); })
            ->toJson();
        $inbound->save();
        $inbound->message()->save($basic);

        return $basic;
    }

    /**
     * Create out-going messages. We keep track of sent messages for further usage
     *
     * @param array $fields
     * @return Message|null
     */
    protected function createOutbound(Array $fields)
    {
        $basic = $this->createBasic($fields);

        $outbound = new Outbound;
        $outbound->content = collect($fields)
            ->filter(function ($name) { return ! in_array($name, $this->common); })
            ->toJson();
        $outbound->save();

        $outbound->message()->save($basic);
        return $basic;
    }

    /**
     * Create event messages. If the message is reporting location, we use
     * eventKey to store coordinates in order to save estate.
     *
     * @param array $fields
     * @return Message|null
     */
    protected function createEvent(Array $fields)
    {
        $basic = $this->createBasic($fields);

        $event = new Event;
        $extra = collect($fields)->diff($this->common);
        $event->event       = Str::lower($extra->get('Event'));
        $event->eventKey    = $extra->get('EventKey');
        $event->ticket      = $extra->get('Ticket');
        if ($extra->has('Latitude')) {
            $event->eventKey = implode(';', $extra->except('Latitude', 'Longitude', 'Precision')-values()->toArray());
        }
        $event->save();

        $event->message()->save($basic);
        return $basic;
    }
}