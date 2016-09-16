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
    protected $name = "deleteevent";

    /**
     * @var string Command Description
     */
    protected $description = "Delete An Event for your group";

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

        $event = Event::where('chat_id', $chatId)->count();

        $text = "";
        if ($event == 0) {
            $text = "You don't got no event to delete cuz.";
        } else {
            $text = "Which event would you like to delete?";
        }


        $this->replyWithMessage(['text' => $text]);

        // This will update the chat status to typing...
    }
}

