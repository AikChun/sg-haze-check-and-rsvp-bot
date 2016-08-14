<?php

namespace App\Bots\Commands\RsvpBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Event;
use Redis;
use Log;
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

        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $message = $this->getUpdate()->getMessage();
        $messageId = $message->getMessageId();
        $chatId = $message->getChat()->getId();

        $event = Event::where('chat_id', $chatId)->count();

        $text = "";
        if ($event > 0) {
            $text = "You already have an event created! Delete before starting a new one.";
        } else {

            $forceReply = $this->getTelegram()->forceReply(['force_reply' => true, 'selective' => true]);

            $text = "What is your event?";
            Redis::set($message->getFrom()->getId(), 'event.created');
            $status = Redis::get($message->getFrom()->getId());
            Log::info($status);
            $this->replyWithMessage(['text' => $text, 'reply_to_message_id' => $this->getUpdate()->getMessage()->getMessageId(), 'reply_markup' => $forceReply]);
        }




        // This will update the chat status to typing...
    }

}
