<?php

namespace App\Bots\Commands\RsvpBot;

use App\Event;
use App\Attendee;
use Log;

class CommandUtil
{
    public static function prepareText($event, $attendees)
    {
        $text = "Event: \n";
        $text .= $event['description'] . "\n\n";
        $i = 0;
        foreach ($attendees as $attendee) {
            $text .=  $attendee['username'] . "\n";
            $i = $i + $attendee['counter'];
        }
        $text .= "\nNumber of attendees: " . $i . "\n";
        $text .= "Click here to attend!\n";
        $text .= "/attending";

        return $text;
    }

    public static function findFriendOrNew($event, $friend)
    {
        return  Attendee::firstOrNew(['event_id' => $event['id'], 'username' => $friend]);
    }

    public static function findAllAttendees($event)
    {
        $attendees = Attendee::where('event_id', $event['id'])->get();

        return $attendees;
    }

    public static function getAttendanceList($event)
    {
        $attendees = self::findAllAttendees($event);
        return self::prepareText($event, $attendees);
    }
}
