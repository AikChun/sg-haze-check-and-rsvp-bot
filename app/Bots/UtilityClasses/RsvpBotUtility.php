<?php
namespace App\Bots\UtilityClasses;

use App\Event;
use App\Attendee;
use Log;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Chat;

class RsvpBotUtility
{
    /**
     * chatHasEvent - check if there an event already created for the chat.
     *
     * @param mixed $identifier - Could be Telegram SDK Message object, Update Object, Chat Object or just an int
     * @return boolean true if there's an event already in the chat or false otherwise.
     */
    public static function chatHasEvent($identifier)
    {
        $chatId = self::retrieveChatId($identifier);

        if($chatId == null) {
            return false;
        }

        $event = event::where('chat_id', $chatId)->count();

        return $event > 0;
    }

    public static function retrieveChatId($identifier)
    {
        $chatId = null;

        if($identifier instanceof Update) {
            $identifier = $identifier->getMessage();
        }

        if($identifier instanceof Message) {
            $identifier = $identifier->getChat();
        }

        if($identifier instanceof Chat) {
            $identifier = $identifier->getId();
        }

        if(is_int($identifier)) {
            $chatId = $identifier;
        }

        return $chatId;
    }

    public static function retrieveMessageId($identifier)
    {
        $messageId = null;

        if($identifier instanceof Update) {
            $identifier = $identifier->getMessage();
        }

        if($identifier instanceof Message) {
            $identifier = $identifier->getMessageId();
        }

        if(is_int($identifier)) {
            $messageId = $identifier;
        }

        return $messageId;
    }

    public static function retrieveMessage($identifier)
    {

        if($identifier instanceof Update) {
            $identifier = $identifier->getMessage();
        }

        if($identifier instanceof Message) {
            return $identifier;
        }

        return null;
    }

    public static function getFromId($identifier)
    {
        $message = self::retrieveMessage($identifier);

        return $message != null ? $message->getFrom()->getId() : null;
    }

    public static function getEventDetails($identifier)
    {
        $text = "";
        if($identifier instanceof Event) {
            $text = $identifier->printEventDetails();
        } else if(is_int($identifier)) {
            $text = Event::where('id', $identifier)->first()->printEventDetails();
        }

        return $text;
    }

    public static function retrieveMessageText($identifier)
    {
        $message = self::retrieveMessage($identifier);
        return $message == null ? null : $message->getText();
    }

    public static function prepareText($event, $attendees)
    {
        $text  = "Event: \n";
        $text .= $event->description . "\n\n";
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
        $attendees = self::findAllAttendees($event);
        return self::prepareText($event, $attendees);
    }
}
