<?php

namespace App\Bots\Commands\RsvpBot;

use App\Event;
use App\Attendee;
use Log;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Chat;

class CommandUtil
{
    /**
     * chatHasEvent - check if there an event already created for the chat.
     *
     * @param mixed $identifier - Could be Telegram SDK Message object, Update Object, Chat Object or just an int
     * @return boolean true if there's an event already in the chat or false otherwise.
     */
    public static function chatHasEvent($identifier)
    {
        $chatId = null;

        if($identifier instanceof Update) {
            $chatId = $identifier->getMessage()->getChat()->getId();
        }

        if($identifier instanceof Message) {
            $chatId = $identifier->getChat()->getId();
        }

        if($identifier instanceof Chat) {
            $chatId = $identifier->getId();
        }

        $event = event::where('chat_id', $chatId)->count();

        if ($event > 0) {
            return true;
        }

        return false;
    }
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
        $attendees = Attendee::where('event_id', $event->id)->get();

        return $attendees;
    }

    public static function getAttendanceList($event)
    {
        Log::info('event id: '. $event->id);
        $attendees = self::findAllAttendees($event);
        return self::prepareText($event, $attendees);
    }
}
