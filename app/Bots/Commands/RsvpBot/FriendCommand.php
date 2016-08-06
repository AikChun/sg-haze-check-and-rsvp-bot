<?php

namespace App\Bots\Commands\RsvpBot;

use Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use App\Attendee;

class FriendCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "friend";

    /**
     * @var string Command Description
     */
    protected $description = "Add a friend to the event";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $message  = $this->getUpdate()->getMessage();
        $chatId   = $this->getUpdate()->getMessage()->getChat()->getId();
        $fromUser = $this->getUpdate()->getMessage()->getFrom();

        $event = Event::where('chat_id', $message->getChat()->getId())->first();
        if (!$event) {
            $this->replyWithMessage(['text' => "You have no event to attend."]);
            return false;
        }

        $friendName = trim($arguments);

        if ($friendName == '') {
            $this->replyWithMessage(['text' => " Sorry please enter your couple name."]);
            return false;
        }

        $attendee = $this->findFriendOrNew($event, $friendName);

        $attendee['username'] = $friendName;
        $attendee['counter'] = 1;

        $attendee->save();

        $eventAttendees = $this->findAllAttendees($event);

        $text = $this->prepareText($event, $eventAttendees);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.

        // Reply with the commands list
        $this->replyWithMessage(['text' => $text]);
    }

    public function getArgumentName($arguments)
    {
        if (preg_match('/\s/', trim($arguments)) > 0) {
            $pieces = explode(' ', $arguments);
            return $pieces[0];
        }

        return $arguments;
    }

    private function findFriendOrNew($event, $friend)
    {
        return  Attendee::firstOrNew(['event_id' => $event['id'], 'username' => $friend]);
    }


    private function findAllAttendees($event)
    {
        $attendees = Attendee::where('event_id', $event['id'])->get();

        return $attendees;
    }

    private function prepareText($event, $attendees)
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

    private function isNotAttending($attendees, $newAttendee)
    {
        $attended = $attendees->filter(function ($attendee) use ($newAttendee) {
            return $attendee->user_id == $newAttendee->user_id;
        })->toArray();

        return (empty($attended));
    }
}
