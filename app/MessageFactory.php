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
     * Create a message from array of attributes
     *
     * @param Array  $fields
     * @param String $kind
     */
    public function create($fields, $kind)
    {
        $factoryMethod = collect([
            'inbound'   => 'createInbound',
            'outbound'  => 'createOutbound',
            'event'     => 'createEvent'
        ])->get($kind, 'createOutbound');

        return $this->$factoryMethod($fields);
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
        collect($this->common)->map(function ($name) use ($message, $fields) {
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
            ->filter(function ($val, $name) { return ! in_array(Str::camel($name), array_merge($this->common, ['msgId'])); })
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
            ->filter(function ($val, $name) { return ! in_array(Str::camel($name), $this->common); })
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
        $event->ticket      = $extra->get('Ticket');
        $event->eventKey    = $extra->get('EventKey');
        if ($extra->has('Latitude')) {
            $event->eventKey = implode(';', $extra->only('Latitude', 'Longitude', 'Precision')->values()->toArray());
        }
        $event->save();

        $event->message()->save($basic);
        return $basic;
    }
}
