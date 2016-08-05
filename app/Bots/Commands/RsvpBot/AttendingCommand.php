<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use App\Attendee;

class AttendingCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "attending";

    /**
     * @var string Command Description
     */
    protected $description = "Attend the event!";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $chatId       = $this->getUpdate()->getMessage()->getChat()->getId();
        $fromUser     = $this->getUpdate()->getMessage()->getFrom();
        $fromUserId   = $fromUser->getId();

        $fromUserName = $fromUser->getFirstName();
        if ($fromUser->getUsername() != "") {
            $fromUserName = $fromUser->getUsername();
        }

        $event = Event::find(['chat_id' => $chatId])->first();

        $attendee = new Attendee;

        $attendee->event_id = $event['id'];
        $attendee->user_id  = $fromUserId;
        $attendee->username = $fromUserName;

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

    private function findAllAttendees($event)
    {
        $attendees = Attendee::find(['event_id' => $event['id']])->get();

        return $attendees;
    }

    private function prepareText($event, $attendees)
    {
        $text = "Event: \n\n";
        $text .= $event['description'] . "\n\n";
        $i = 1;
        foreach ($attendees as $attendee) {
            $text .= $i . ". " . $attendee->username . "\n\n";
            $i = $i + 1;
        }

        return $text;
    }
}
