<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;

class DeleteEventCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "viewevent";

    /**
     * @var string Command Description
     */
    protected $description = "View your event";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        //
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();

        $event = Event::where('chat_id' => $chatId)->count();

        $text = "";
        if($event == 0) {
            $text = "You don't got not event, son!";
        } else {

            $event = Event::where('chat_id' => $chatId)->first();

            $attendees = Attendee::where('event_id', $event['id']);

            $text = $this->prepareText($event, $attendees);

        }



        $this->replyWithMessage(['text' => $text]);

        // This will update the chat status to typing...

    }

    private function prepareText($event, $attendees)
    {
        $text = "Event: \n\n";
        $text .= $event['description'] . "\n\n";
        $i = 1;
        foreach ($attendees as $attendee) {
            $text .= $i . ". " . $attendee['username'] . "\n\n";
            $i = $i + 1;
        }

        return $text;
    }

}
