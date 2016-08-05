<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;

class ThreeHourPsiUpdateCommand extends Command
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
        $event = new Event;

        $event->chat_id     = $this->getUpdate()->getMessage()->getChat()->getId();
        $event->description = $arguments;

        $event->save();

        $text = $this->announceEventCreated($arguments);

        $this->replyWithMessage(['text' => $text]);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

    }

    private function announceEventCreated($data)
    {
        $text = "You have created event: \n";
        $text .= $data;

        return $text;
    }



}
