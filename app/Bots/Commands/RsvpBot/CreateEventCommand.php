<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use App\Bots\Commands\CommandsUtil;

class CreateEventCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "createevent";

    /**
     * @var string Command Description
     */
    protected $description = "Create An Event for your group chat";

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

        CommandsUtil::getMessageChatType($this->getUpdate());
        die;

        $event = Event::where('chat_id', $chatId)->count();

        $text = "";
        if ($event > 0) {
            $text = "You already have an event created! Delete before starting a new one.";
        } else {
            $event = new Event;

            $event->chat_id     = $chatId;
            $event->description = $arguments;

            $event->save();
            $text = $this->announceEventCreated($arguments);
        }



        $this->replyWithMessage(['text' => $text]);

        // This will update the chat status to typing...
    }

    private function announceEventCreated($data)
    {
        $text = "Event: \n";
        $text .= $data . "\n\n";
        $text .= "Click here to attend!\n";
        $text .= "/attending";

        return $text;
    }
}
