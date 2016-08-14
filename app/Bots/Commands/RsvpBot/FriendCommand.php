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

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $forceReply = $this->getTelegram()->forceReply(['force_reply' => true, 'selective' => true]);

        $text = "What is your friend's name?";

        $this->replyWithMessage(['text' => $text, 'reply_to_message_id' => $message->getMessageId(), 'reply_markup' => $forceReply]);

    }
}
